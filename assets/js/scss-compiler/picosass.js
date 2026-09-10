/**
 * Picosass.js
 * Client-side SCSS compiler for Bootscore, using Dart Sass loaded in the
 * browser. Ported and adapted from picostrap5.
 *
 * @package Bootscore
 * @version 7.0.0
 */


import * as sass from 'https://cdn.jsdelivr.net/npm/sass@1.104.0/sass.default.js'; //import SASS module (raw, unbundled - needs the importmap for "immutable" declared in scss-compiler.php)

//console.log(sass.compileStringAsync(` .box {width: 10px + 15px;} `)); //just a quick example of compilation

const theScssSelector = '#the-scss'; //the selector for the element containing the SCSS code element

//SUPPORT FUNCTION 
const replaceLast = (str, pattern, replacement) => {
    const match =
        typeof pattern === 'string'
            ? pattern
            : (str.match(new RegExp(pattern.source, 'g')) || []).slice(-1)[0];
    if (!match) return str;
    const last = str.lastIndexOf(match);
    return last !== -1
        ? `${str.slice(0, last)}${replacement}${str.slice(last + match.length)}`
        : str;
};

//SUPPORT FUNCTIONS FOR STRING MEASURING
function measureStringSizeInKB(str) {
    const encoder = new TextEncoder('utf-8');
    const bytes = encoder.encode(str);
    const sizeInKB = bytes.length / 1024; // Convert bytes to kilobytes
    return Math.floor(sizeInKB);
}
function measureEstimatedGzippedSizeInKB(str) {
    // Convert the string to bytes
    const encoder = new TextEncoder('utf-8');
    const bytes = encoder.encode(str);

    // Create a basic "gzip-like" compression (run-length encoding)
    const compressedBytes = basicGzip(bytes);

    // Calculate an estimated size in kilobytes based on a factor (e.g., 0.1)
    const sizeInKB = (compressedBytes.length / 1024) * 0.074; // Adjust the factor as needed

    return Math.floor(sizeInKB);
}
function basicGzip(inputBytes) {
    // Create a basic "gzip-like" compression (just simple run-length encoding)
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

//cache of resolved fetches, keyed by canonical URL - populated by canonicalize()'s
//probing so load() doesn't have to fetch the same file twice
const resolvedFileCache = new Map();

//try fetching a URL, falling back to fallback_baseurl (child theme -> parent theme) on 404
async function fetchWithThemeFallback(url, options) {
    let response = await fetch(url, options);
    let actualUrl = url;

    if (!response.ok && document.querySelector(theScssSelector).hasAttribute("fallback_baseurl")) {
        const urlFallback = url.href.replace(
            (document.querySelector(theScssSelector).getAttribute("baseurl")),
            (document.querySelector(theScssSelector).getAttribute("fallback_baseurl")
            ));
        console.log('Since ' + url.href + ' cannot be found, we look for ' + urlFallback);
        response = await fetch(urlFallback, options);
        if (response.ok) {
            actualUrl = new URL(urlFallback);
        }
    }

    return { response, actualUrl };
}

//FUNCTIONS TO ALLOW COMPILER TO READ FILES FROM THE WEB 
async function canonicalize(url) {
    //console.log('canonicalize ' + url);

    //if it's not the main file, or the main bs file, add underscores in front of scss file names
    //(handles both nested paths like "folder/name" AND bare same-folder names like "name")
    if (!url.endsWith("/main") && !url.endsWith("/bootstrap") && url !== "main" && url !== "bootstrap") {
        if (url.includes('/')) {
            url = replaceLast(url, '/', '/_');
        } else {
            url = '_' + url;
        }
    }

    //build the request options: if nocache parameter is set, declare it, or just have an empty one.
    const options = (((new URL(document.location)).searchParams).get("sass_nocache")) ? { cache: "no-cache" } : {}

    const base = document.querySelector(theScssSelector).getAttribute("baseurl") ?? window.location.toString();
    const primaryUrl = new URL(url + '.scss', base);

    //show some feedback about the file being resolved
    if (document.querySelector("#picosass-output-feedback span")) {
        document.querySelector("#picosass-output-feedback span").innerHTML = `Importing file: <br>${primaryUrl}`;
    }

    //try the plain file first (child theme, then parent theme fallback)
    let { response, actualUrl } = await fetchWithThemeFallback(primaryUrl, options);
    let canonicalUrl = primaryUrl;

    //if that 404s, this might be a directory-style module (e.g. "content" resolving
    //to a directory's index file instead of a plain _content.scss file) - try both
    //_index.scss and index.scss (real Sass resolves either), each with the same
    //child/parent theme fallback. Whichever one actually resolves becomes the real
    //canonical URL, so further relative @use/@forward inside it resolve against the
    //correct directory instead of the shallower, non-existent flat-file location.
    if (!response.ok) {
        const dirIndexCandidates = [
            primaryUrl.href.replace(/\/_([^/]+)\.scss$/, '/$1/_index.scss'),
            primaryUrl.href.replace(/\/_([^/]+)\.scss$/, '/$1/index.scss'),
        ];
        for (const candidateHref of dirIndexCandidates) {
            if (candidateHref === primaryUrl.href) continue;
            const candidateUrl = new URL(candidateHref);
            ({ response, actualUrl } = await fetchWithThemeFallback(candidateUrl, options));
            if (response.ok) {
                canonicalUrl = candidateUrl;
                break;
            }
        }
    }

    //cache whatever we found (success or failure) so load() doesn't re-fetch;
    //on failure this still lets load() produce the same clear error as before
    resolvedFileCache.set(canonicalUrl.href, { response, actualUrl, originalRequestUrl: primaryUrl });

    return canonicalUrl;
}

async function load(canonicalUrl) {

    //console.log(`Importing ${canonicalUrl} (async)`);

    const cached = resolvedFileCache.get(canonicalUrl.href);
    const { response, actualUrl, originalRequestUrl } = cached ?? {};

    if (!response || !response.ok) {
        const shownUrl = originalRequestUrl ?? canonicalUrl;
        document.querySelector("#picosass-output-feedback").innerHTML = `Error reading   SCSS file:  ${shownUrl} <span>${response ? `${response.status} (${response.statusText})` : 'not found'}</span>`;
        throw new Error(`Failed to fetch ${shownUrl}${response ? `: ${response.status} (${response.statusText})` : ''}`);
    }
    const contents = await response.text()

    //return full URL for source map (required by SASS compiler to be absolute)
    return {
        contents,
        syntax: canonicalUrl.pathname.endsWith('.sass') ? 'indented' : 'scss',
        sourceMapUrl: actualUrl
    }
}

async function runScssCompiler(theCode, sassParams) {

    //SMART DEFAULTS FOR THE COMPILER

    //set default output
    if (!sassParams.style) sassParams.style = "compressed";

    //set default importers
    if (!sassParams.importers) sassParams.importers = [{ canonicalize, load }];

    //set default charset
    if (!sassParams.charset) sassParams.charset = false;

    //set default source map to include actual file URLs
    if (sassParams.sourceMap === undefined) sassParams.sourceMap = true;
    if (sassParams.sourceMapIncludeSources === undefined) sassParams.sourceMapIncludeSources = false;

    //set default URL for the source file to enable proper source map generation
    //use stdin.scss as a virtual entry point to avoid circular import issues
    if (!sassParams.url) {
        const base = document.querySelector(theScssSelector)?.getAttribute("baseurl") ?? window.location.toString();
        sassParams.url = new URL('stdin.scss', base);
    }

    return await sass.compileStringAsync(theCode, sassParams)
}

export function Compile(sassParams = {}, theCallback = () => { }) {

    //for debug
    console.log("PicoSASS Compile launched");

    //if not present, add a DIV and some styling TO SHOW COMPILER MESSAGES / OUTPUT FEEDBACK 
    if (!document.querySelector("#picosass-output-feedback")) document.querySelector("html").insertAdjacentHTML("afterbegin", `
        <div id='picosass-output-feedback'></div> 
        <style> 
            #picosass-output-feedback { position:fixed; right:1rem; bottom:1rem; left:1rem; z-index: 9999; font-size:1.25rem; background:#CBF2FF; color:#212529; font-family:courier; border:1px solid #42D6FD; border-radius: .5rem; padding:1rem; display:block;   word-wrap: break-word;   }
            #picosass-output-feedback span{display:block; font-size:1rem; z-index:0; margin-top: .5rem}
            #picosass-output-feedback:empty {display:none}
        </style>
        `);

    //is a Compile process already running? if so, abort
    //TODO: make it cleaner, this is just a dirty implementation
    if (document.querySelector("#picosass-output-feedback").innerHTML.includes('Compiling')) {
        console.log("PicoSASS task is already running, retrying in a few secs.");
        setTimeout(function () {
            Compile(sassParams, theCallback);
        }, 2000);

        return false;
    }

    //if no SCSS source element is on the page, show message: No SCSS element to compile...
    if (!document.querySelector(theScssSelector)) document.querySelector("#picosass-output-feedback").innerHTML = ` No SCSS element to compile... `;

    //if SCSS source element is empty, exit
    const theCode = document.querySelector(theScssSelector).innerHTML;
    if (theCode.trim() == '') {
        console.log("Empty SCSS source, aborting");
        return false;
    }

    //show the first feedback message: Compiling .... 
    document.querySelector("#picosass-output-feedback").innerHTML = `Compiling SCSS... <span></span>`;
    console.log("Compiling SCSS...");

    //measure time
    const timeStart = Date.now();

    //run the compiler
    runScssCompiler(theCode, sassParams)

        .then((compiled) => {
            //console.log("SCSS compiled successfully.");
            //console.log(compiled);
            const timeEnd = Date.now();

            //convert absolute URLs to relative paths in the source map
            let modifiedSourceMap = null;
            if (compiled.sourceMap) {
                modifiedSourceMap = {
                    ...compiled.sourceMap,
                    sources: compiled.sourceMap.sources.map(source => {
                        try {
                            const sourceUrl = new URL(source);
                            //return path relative to base URL (remove origin)
                            return sourceUrl.pathname;
                        } catch (e) {
                            //if source is not a valid URL, return as-is
                            return source;
                        }
                    })
                };
            }

            //if not present, add a new CSS element
            if (!document.querySelector("#picosass-injected-style")) document.head.insertAdjacentHTML("beforeend", `<style id="picosass-injected-style"> </style>`);

            //prepare CSS with source map comment if source map exists
            let cssWithSourceMap = compiled.css;
            if (modifiedSourceMap) {
                const sourceMapJson = JSON.stringify(modifiedSourceMap);
                const sourceMapBase64 = btoa(unescape(encodeURIComponent(sourceMapJson)));
                cssWithSourceMap += `\n/*# sourceMappingURL=data:application/json;charset=utf-8;base64,${sourceMapBase64} */`;
            }

            //populate the element with the new CSS
            document.querySelector('#picosass-injected-style').innerHTML = cssWithSourceMap;

            //remove initial static CSS, if present (just to prevent FOUC)
            document.querySelector(".picostrap-provisional-css")?.setAttribute("disabled", "true");

            //show compiled size
            const theFeedback = `SCSS compiled successfully. <span>Approx. CSS bundle size:  ${measureStringSizeInKB(compiled.css)} KB (${measureEstimatedGzippedSizeInKB(compiled.css)} KB gzipped) </span><span>Execution time: ${(timeEnd - timeStart) / 1000} secs</span>`;
            document.querySelector("#picosass-output-feedback").innerHTML = theFeedback;
            console.log(theFeedback.replace(/(<([^>]+)>)/ig, ''));

            //as there are no errors, clear the output feedback
            const myTimeout = setTimeout(() => { document.querySelector("#picosass-output-feedback").innerHTML = ''; }, 4500);

            //run callback with modified source map
            theCallback({
                ...compiled,
                sourceMap: modifiedSourceMap || compiled.sourceMap
            });
        })

        .catch((error) => {
            //show error in output feedback 
            document.querySelector("#picosass-output-feedback").innerHTML = `SCSS error <span> ${error} </span> `;
        })
}

//MAKE THE COMPILE FUNCTION  GLOBALLY AVAILABLE
//eg: window.Picosass.Compile();
//or: window.Picosass.Compile({style: "expanded"});

window.Picosass = {
    Compile: Compile,
    Run: runScssCompiler
}


/////////////////////////////// ON DOM CONTENT LOADED: COMPILE ONCE & OBSERVE CHANGES TO SOURCE SCSS //////////////
window.addEventListener("DOMContentLoaded", (event) => {

    //run  the compiler, unless a special class is added to the body
    if (!document.querySelector(theScssSelector).classList.contains("prevent-autocompile")) {
        Compile();
    }

}); //end onDOMContentLoaded
