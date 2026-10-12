/**
 * ts-compiler.js
 * Client-side TypeScript compiler/bundler for Bootscore, using esbuild-wasm
 * loaded in the browser. Parallel to picosass.js, but for the Bootstrap 6
 * .ts source instead of SCSS.
 *
 * @package Bootscore
 * @version 7.0.0
 */

import * as esbuild from 'https://cdn.jsdelivr.net/npm/esbuild-wasm@0.28.0/esm/browser.min.js';

const theTsSelector = '#the-ts'; // the selector for the element containing the TS entry-point code
const tsNamespace = 'bootscore-ts'; // theme's own .ts source
const npmNamespace = 'bootscore-npm'; // third-party packages (vanilla-calendar-pro, @floating-ui/dom, etc.), loaded via CDN

//SUPPORT FUNCTIONS FOR STRING MEASURING (duplicated from picosass.js - kept
//standalone since this loads as its own <script type="module">, no shared scope)
function measureStringSizeInKB(str) {
  const encoder = new TextEncoder('utf-8');
  const bytes = encoder.encode(str);
  return Math.floor(bytes.length / 1024);
}
function measureEstimatedGzippedSizeInKB(str) {
  const encoder = new TextEncoder('utf-8');
  const bytes = encoder.encode(str);
  const compressedBytes = basicGzip(bytes);
  return Math.floor((compressedBytes.length / 1024) * 0.074);
}
function basicGzip(inputBytes) {
  let compressedBytes = [];
  let currentByte = inputBytes[0];
  let count = 1;
  for (let i = 1; i < inputBytes.length; i++) {
    if (inputBytes[i] === currentByte && count < 255) {
      count++;
    } else {
      compressedBytes.push(count, currentByte);
      currentByte = inputBytes[i];
      count = 1;
    }
  }
  compressedBytes.push(count, currentByte);
  return new Uint8Array(compressedBytes);
}

//esbuild-wasm needs one-time async initialization before build() can run -
//cache the promise so repeated Compile() calls (e.g. "Recompile Continuously") don't re-init
let esbuildInitPromise = null;
function ensureEsbuildInitialized() {
  if (!esbuildInitPromise) {
    esbuildInitPromise = esbuild.initialize({
      wasmURL: 'https://cdn.jsdelivr.net/npm/esbuild-wasm@0.28.0/esbuild.wasm',
    });
  }
  return esbuildInitPromise;
}

//try fetching a URL, falling back to fallback_baseurl (child theme -> parent theme) on 404
//identical pattern to picosass.js's fetchWithThemeFallback, just reading #the-ts instead
async function fetchWithThemeFallback(url, options) {
  let response = await fetch(url, options);
  let actualUrl = url;

  if (!response.ok && document.querySelector(theTsSelector).hasAttribute("fallback_baseurl")) {
    const urlFallback = url.href.replace(
      (document.querySelector(theTsSelector).getAttribute("baseurl")),
      (document.querySelector(theTsSelector).getAttribute("fallback_baseurl")
      ));
    console.log('Since ' + url.href + ' cannot be found, we look for ' + urlFallback);
    response = await fetch(urlFallback, options);
    if (response.ok) {
      actualUrl = new URL(urlFallback);
    }
  }

  return { response, actualUrl };
}

//pull Bootstrap's own version constant out of base-component.ts, so the banner
//always matches whatever .ts source is currently pasted into assets/ts/ - no
//manual bump needed on version updates
async function getBootstrapVersion() {
  try {
    const base = document.querySelector(theTsSelector).getAttribute("baseurl") ?? window.location.toString();
    const url = new URL('bootstrap/base-component.ts', base);
    const { response } = await fetchWithThemeFallback(url, {});
    if (!response.ok) return null;
    const contents = await response.text();
    const match = contents.match(/const VERSION = ['"]([^'"]+)['"]/);
    return match ? match[1] : null;
  } catch (e) {
    return null;
  }
}

//resolve an import specifier to an absolute URL, swapping the trailing .js -> .ts
//(Bootstrap 6's TS source imports sibling files as "./alert.js" even though the
//real file on disk is "alert.ts" - standard TS+ESM convention, resolved at compile time)
function resolveTsUrl(path, importerUrl) {
  const base = document.querySelector(theTsSelector).getAttribute("baseurl") ?? window.location.toString();
  const resolved = importerUrl ? new URL(path, importerUrl) : new URL(path, base);

  if (resolved.pathname.endsWith('.js')) {
    resolved.pathname = resolved.pathname.slice(0, -3) + '.ts';
  }

  return resolved;
}

//a bare specifier ("vanilla-calendar-pro", "@floating-ui/dom") is a real npm
//package, not part of the theme's own .ts tree - anything starting with "." or
//"/" or already a full URL is the theme's own relative/absolute source instead
function isBareSpecifier(path) {
  return !path.startsWith('.') && !path.startsWith('/') && !path.includes('://');
}

//FUNCTIONS TO ALLOW THE COMPILER TO READ FILES FROM THE WEB
//(esbuild plugin API: onResolve decides the path, onLoad fetches + returns contents -
//no separate canonicalize/load phases like Sass's importer API)
const bootscoreTsPlugin = {
  name: 'bootscore-ts',
  setup(build) {

    build.onResolve({ filter: /.*/ }, (args) => {
      // already inside a CDN-resolved package - resolve its relative imports
      // against it directly (real .js, no .ts rewrite); a bare import here is a
      // dependency-of-a-dependency, routed through the same CDN
      if (args.namespace === npmNamespace) {
        if (isBareSpecifier(args.path)) {
          return { path: new URL(`https://esm.sh/${args.path}`).href, namespace: npmNamespace };
        }
        return { path: new URL(args.path, args.importer).href, namespace: npmNamespace };
      }

      // a bare specifier reached from the theme's own .ts source - third-party
      // dependency, not part of Bootstrap's .ts tree, resolve via CDN instead
      if (isBareSpecifier(args.path)) {
        return { path: new URL(`https://esm.sh/${args.path}`).href, namespace: npmNamespace };
      }

      // theme's own relative .ts source, resolved + rewritten against baseurl.
      // imports originating from the embedded stdin entry point (index.ts) report
      // some non-absolute importer (confirmed via testing to not be a real URL,
      // likely the literal sourcefile string "index.ts") - treat anything that
      // doesn't parse as an absolute URL as "relative to baseurl" instead
      let importerUrl = null;
      if (args.importer) {
        try {
          importerUrl = new URL(args.importer);
        } catch (e) {
          importerUrl = null;
        }
      }

      const resolved = resolveTsUrl(args.path, importerUrl);
      return { path: resolved.href, namespace: tsNamespace };
    });

    build.onLoad({ filter: /.*/, namespace: tsNamespace }, async (args) => {
      const url = new URL(args.path);
      const nocache = (new URL(document.location)).searchParams.get("ts_nocache");
      const options = nocache ? { cache: "no-cache" } : {};

      if (document.querySelector("#ts-compiler-output-feedback span")) {
        document.querySelector("#ts-compiler-output-feedback span").innerHTML = `Importing file: <br>${url}`;
      }

      const { response } = await fetchWithThemeFallback(url, options);

      if (!response.ok) {
        document.querySelector("#ts-compiler-output-feedback").innerHTML = `Error reading TS file: ${url} <span>${response.status} (${response.statusText})</span>`;
        throw new Error(`Failed to fetch ${url}: ${response.status} (${response.statusText})`);
      }

      const contents = await response.text();
      const loader = url.pathname.endsWith('.tsx') ? 'tsx' : 'ts';

      return { contents, loader };
    });

    build.onLoad({ filter: /.*/, namespace: npmNamespace }, async (args) => {
      const url = new URL(args.path);

      if (document.querySelector("#ts-compiler-output-feedback span")) {
        document.querySelector("#ts-compiler-output-feedback span").innerHTML = `Fetching dependency: <br>${url}`;
      }

      const response = await fetch(url);
      if (!response.ok) {
        document.querySelector("#ts-compiler-output-feedback").innerHTML = `Error fetching dependency: ${url} <span>${response.status} (${response.statusText})</span>`;
        throw new Error(`Failed to fetch ${url}: ${response.status} (${response.statusText})`);
      }

      const contents = await response.text();
      return { contents, loader: 'js' };
    });
  }
};

async function runTsCompiler(theCode, esbuildParams) {

  await ensureEsbuildInitialized();

  //SMART DEFAULTS FOR THE COMPILER

  // DECISION NEEDED: 'iife' + a global (matching Bootstrap's own dist/js/bootstrap.bundle.min.js
  // UMD-style global) keeps enqueue.php able to use a plain <script> tag, same as today.
  // 'esm' would require switching the enqueue to <script type="module">. Flag if you'd rather
  // go the module route instead - affects how bootscore.min.js gets loaded in enqueue.php later.
  if (!esbuildParams.bundle) esbuildParams.bundle = true;
  if (!esbuildParams.format) esbuildParams.format = 'iife';
  if (!esbuildParams.globalName) esbuildParams.globalName = 'bootstrap';
  if (!esbuildParams.target) esbuildParams.target = 'es2018';
  if (esbuildParams.minify === undefined) esbuildParams.minify = true;
  if (esbuildParams.sourcemap === undefined) esbuildParams.sourcemap = 'external';

  // Strips Bootstrap's/dependencies' own scattered MIT license banners entirely -
  // Compile() injects one clean canonical banner instead (see getBootstrapVersion()
  // and the banner block below), so nothing is lost, just consolidated.
  if (!esbuildParams.legalComments) esbuildParams.legalComments = 'none';

  if (!esbuildParams.outfile) esbuildParams.outfile = 'bootscore.min.js';
  if (!esbuildParams.plugins) esbuildParams.plugins = [bootscoreTsPlugin];

  esbuildParams.write = false;
  esbuildParams.stdin = {
    contents: theCode,
    loader: 'ts',
    sourcefile: 'index.ts',
  };

  return await esbuild.build(esbuildParams);
}

export async function Compile(esbuildParams = {}, theCallback = () => { }) {

  console.log("Bootscore TS Compiler launched");

  if (!document.querySelector("#ts-compiler-output-feedback")) document.querySelector("html").insertAdjacentHTML("afterbegin", `
        <div id='ts-compiler-output-feedback'></div>
        <style>
            #ts-compiler-output-feedback { position:fixed; right:1rem; bottom:9.5rem; left:1rem; z-index: 9999; font-size:1.25rem; background:#CBF2FF; color:#212529; font-family:courier; border:1px solid #42D6FD; border-radius: .5rem; padding:1rem; display:block; word-wrap: break-word; }
            #ts-compiler-output-feedback span{display:block; font-size:1rem; z-index:0; margin-top: .5rem}
            #ts-compiler-output-feedback:empty {display:none}
        </style>
        `);

  if (document.querySelector("#ts-compiler-output-feedback").innerHTML.includes('Compiling')) {
    console.log("TS compiler task is already running, retrying in a few secs.");
    setTimeout(function () {
      Compile(esbuildParams, theCallback);
    }, 2000);
    return false;
  }

  if (!document.querySelector(theTsSelector)) document.querySelector("#ts-compiler-output-feedback").innerHTML = ` No TS element to compile... `;

  const theCode = document.querySelector(theTsSelector).innerHTML;
  if (theCode.trim() == '') {
    console.log("Empty TS source, aborting");
    return false;
  }

  document.querySelector("#ts-compiler-output-feedback").innerHTML = `Compiling TS... <span></span>`;
  console.log("Compiling TS...");

  if (!esbuildParams.banner) {
    const bootstrapVersion = await getBootstrapVersion();
    if (bootstrapVersion) {
      const copyrightYear = `2011-${new Date().getFullYear()}`;
      esbuildParams.banner = {
        js: `/*!\n * Bootstrap v${bootstrapVersion} (https://getbootstrap.com/)\n * Copyright ${copyrightYear} The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)\n * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)\n */`
      };
    }
  }

  const timeStart = Date.now();

  runTsCompiler(theCode, esbuildParams)

    .then((result) => {
      const timeEnd = Date.now();

      const jsFile = result.outputFiles.find(f => !f.path.endsWith('.map'));
      const mapFile = result.outputFiles.find(f => f.path.endsWith('.map'));

      const js = jsFile ? jsFile.text : '';
      const sourceMap = mapFile ? JSON.parse(mapFile.text) : null;

      const theFeedback = `TS compiled successfully. <span>Approx. JS bundle size: ${measureStringSizeInKB(js)} KB (${measureEstimatedGzippedSizeInKB(js)} KB gzipped)</span><span>Execution time: ${(timeEnd - timeStart) / 1000} secs</span>`;
      document.querySelector("#ts-compiler-output-feedback").innerHTML = theFeedback;
      console.log(theFeedback.replace(/(<([^>]+)>)/ig, ''));

      setTimeout(() => { document.querySelector("#ts-compiler-output-feedback").innerHTML = ''; }, 4500);

      theCallback({ js, sourceMap });
    })

    .catch((error) => {
      document.querySelector("#ts-compiler-output-feedback").innerHTML = `TS error <span>${error}</span>`;
    })
}

//MAKE THE COMPILE FUNCTION GLOBALLY AVAILABLE
//eg: window.BootscoreTSCompiler.Compile();
window.BootscoreTSCompiler = {
  Compile: Compile,
  Run: runTsCompiler
}

/////////////////////////////// ON DOM CONTENT LOADED: COMPILE ONCE //////////////
window.addEventListener("DOMContentLoaded", (event) => {
  if (!document.querySelector(theTsSelector).classList.contains("prevent-autocompile")) {
    Compile();
  }
});
