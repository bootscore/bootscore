/**
 * Bootscore wrapper block: a minimal semantic wrapper with tag, class and custom attributes.
 *
 * @package Bootscore
 * @version 7.0.0
 */

( function ( blocks, element, blockEditor, components, i18n, data, plugins, editor ) {
  var el = element.createElement;
  var InnerBlocks = blockEditor.InnerBlocks;
  var InspectorControls = blockEditor.InspectorControls;
  var BlockControls = blockEditor.BlockControls;
  var useBlockProps = blockEditor.useBlockProps;
  var useInnerBlocksProps = blockEditor.useInnerBlocksProps;
  var PanelBody = components.PanelBody;
  var SelectControl = components.SelectControl;
  var TextControl = components.TextControl;
  var Button = components.Button;
  var ToolbarGroup = components.ToolbarGroup;
  var ToolbarButton = components.ToolbarButton;
  var __ = i18n.__;
  var createBlock = blocks.createBlock;
  var dispatch = data.dispatch;
  var select = data.select;
  var registerPlugin = plugins.registerPlugin;
  var PluginBlockSettingsMenuItem =
    editor.PluginBlockSettingsMenuItem ||
    ( window.wp.editPost && window.wp.editPost.PluginBlockSettingsMenuItem );

  var TAG_OPTIONS = [
    { label: 'div', value: 'div' },
    { label: 'span', value: 'span' },
    { label: 'address', value: 'address' },
    { label: 'section', value: 'section' },
    { label: 'article', value: 'article' },
    { label: 'header', value: 'header' },
    { label: 'footer', value: 'footer' },
    { label: 'aside', value: 'aside' },
    { label: 'nav', value: 'nav' },
    { label: 'figure', value: 'figure' },
    { label: 'details', value: 'details' },
    { label: 'summary', value: 'summary' },
    { label: 'dialog', value: 'dialog' },
    { label: 'button', value: 'button' },
    { label: 'ul', value: 'ul' },
    { label: 'li', value: 'li' }
  ];

  // Wrap icon (block icon + "Wrap" menu item)
  var wrapIcon = el( 'svg', {
    xmlns: 'http://www.w3.org/2000/svg',
    viewBox: '0 0 6.35 6.35',
    fill: 'currentColor'
  },
    el( 'path', {
      d: 'm 3.656579,1.4894725 c -0.112457,-0.03241 -0.229544,0.03308 -0.261958,0.145532 l -0.846733,2.963565 c -0.03241,0.112456 0.03308,0.229544 0.145532,0.261958 0.112457,0.03241 0.229544,-0.03308 0.261958,-0.145532 l 0.846733,-2.963565 c 0.03241,-0.112457 -0.03307,-0.229544 -0.145532,-0.261958 z m 0.533177,0.794473 c -0.08269,0.08269 -0.08269,0.216976 0,0.299665 l 0.590728,0.591389 -0.59139,0.59139 c -0.08269,0.08269 -0.08269,0.216976 0,0.299664 0.08269,0.08269 0.216976,0.08269 0.299665,0 L 5.22965,3.3251625 c 0.08269,-0.08269 0.08269,-0.216975 0,-0.299664 L 4.488759,2.2846075 c -0.08269,-0.08269 -0.216976,-0.08269 -0.299665,0 z m -2.028851,0 c -0.08269,-0.08269 -0.216976,-0.08269 -0.299664,0 L 1.12035,3.0248375 c -0.08269,0.08269 -0.08269,0.216975 0,0.299664 l 0.740891,0.740891 c 0.08269,0.08269 0.216975,0.08269 0.299664,0 0.08269,-0.08269 0.08269,-0.216975 0,-0.299664 l -0.59139,-0.590729 0.59139,-0.591389 c 0.08269,-0.08269 0.08269,-0.216976 0,-0.299665 z'
    } )
  );

  // Unwrap icon (toolbar button + "Unwrap" menu item)
  var unwrapIcon = el( 'svg', {
    xmlns: 'http://www.w3.org/2000/svg',
    viewBox: '0 0 6.35 6.35',
    fill: 'currentColor'
  },
    el( 'path', {
      d: 'm 4.9046102,1.2335164 c -0.00155,0 -0.0031,4.831e-4 -0.00465,5.168e-4 -0.00754,1.628e-4 -0.015245,5.828e-4 -0.022738,0.00155 -8.454e-4,1.092e-4 -0.00174,-1.194e-4 -0.00258,0 -0.00103,1.453e-4 -0.00207,3.563e-4 -0.0031,5.168e-4 -0.00802,0.00126 -0.015912,0.00299 -0.023771,0.00517 -0.00158,4.362e-4 -0.00308,0.00108 -0.00465,0.00155 -0.00612,0.00185 -0.012118,0.00378 -0.018087,0.0062 -0.00437,0.00176 -0.00865,0.00362 -0.012919,0.00568 -0.020697,0.010079 -0.040124,0.023588 -0.057361,0.040824 L 1.2955281,4.7547485 c -0.020755,0.020755 -0.036131,0.044777 -0.046509,0.07028 -0.00519,0.012752 -0.00929,0.025928 -0.011886,0.039274 -0.00259,0.013346 -0.00362,0.026764 -0.00362,0.040308 0,0.013544 0.00102,0.026962 0.00362,0.040308 0.00259,0.013346 0.0067,0.026522 0.011886,0.039274 0.00259,0.00638 0.00554,0.01245 0.00879,0.018604 7.2e-5,1.374e-4 -7.24e-5,3.794e-4 0,5.167e-4 0.00319,0.00602 0.00653,0.011815 0.010335,0.01757 8.36e-5,1.268e-4 -8.38e-5,3.901e-4 0,5.168e-4 0.00773,0.011639 0.01711,0.022794 0.027389,0.033073 0.00519,0.00519 0.01046,0.00993 0.01602,0.014469 0.00556,0.00454 0.011172,0.00903 0.017053,0.012919 1.121e-4,7.45e-5 4.045e-4,-7.43e-5 5.167e-4,0 0.00576,0.00379 0.011549,0.00716 0.01757,0.010335 0.00628,0.00333 0.012607,0.00613 0.01912,0.00878 0.012752,0.00519 0.025928,0.00929 0.039274,0.011886 0.013346,0.00259 0.026764,0.00362 0.040308,0.00362 0.013544,0 0.026962,-0.00102 0.040308,-0.00362 0.013346,-0.00259 0.026522,-0.0067 0.039274,-0.011886 0.025503,-0.010377 0.049525,-0.025754 0.07028,-0.046509 l 3.459215,-3.4592155 c 0.020755,-0.020755 0.036131,-0.044777 0.046509,-0.07028 0.00519,-0.012752 0.00929,-0.025928 0.011886,-0.039274 0.00259,-0.013346 0.00362,-0.026764 0.00362,-0.040308 0,-0.013544 -0.00102,-0.026962 -0.00362,-0.040308 -0.00259,-0.013346 -0.0067,-0.026522 -0.011886,-0.039274 -0.00265,-0.00651 -0.00546,-0.01284 -0.00878,-0.01912 -0.00317,-0.00602 -0.00654,-0.011809 -0.010335,-0.01757 -7.43e-5,-1.122e-4 7.45e-5,-4.046e-4 0,-5.167e-4 -0.00389,-0.00588 -0.00838,-0.011494 -0.012919,-0.017053 -0.00454,-0.00556 -0.00928,-0.010831 -0.014469,-0.01602 -0.027245,-0.027245 -0.059993,-0.045414 -0.094568,-0.054777 -0.00543,-0.00147 -0.011026,-0.00259 -0.016536,-0.00362 -1.656e-4,-3.1e-5 -3.512e-4,3.05e-5 -5.168e-4,0 -0.00635,-0.00117 -0.012705,-0.002 -0.01912,-0.00258 -0.00636,-5.701e-4 -0.012738,-0.00103 -0.01912,-0.00103 z M 3.5935791,1.4810465 c -0.090231,0.00204 -0.1726179,0.062629 -0.1989543,0.1539958 L 3.1357259,2.5404134 3.7532592,1.92288 3.8023519,1.7513143 c 0.03241,-0.112457 -0.033266,-0.2295855 -0.1457276,-0.2619995 -0.021086,-0.00608 -0.042223,-0.00874 -0.063045,-0.00827 z M 2.0112467,2.2220866 c -0.054244,0 -0.1085176,0.020667 -0.1498616,0.062012 L 1.120345,3.0246216 c -0.08269,0.08269 -0.08269,0.2170343 0,0.2997233 L 1.7363281,3.9398112 2.0355347,3.6406046 1.5694132,3.175 2.1611084,2.5838216 c 0.08269,-0.08269 0.08269,-0.2170343 0,-0.2997233 -0.041345,-0.041345 -0.095618,-0.062012 -0.1498617,-0.062012 z M 4.6136718,2.4101888 4.3149821,2.7088786 4.7805867,3.175 4.1888916,3.7661784 c -0.08269,0.08269 -0.08269,0.2170353 0,0.2997233 0.08269,0.08269 0.2170343,0.08269 0.2997233,0 l 0.74104,-0.7405233 c 0.08269,-0.08269 0.08269,-0.2170343 0,-0.2997233 z M 3.2142741,3.8095866 2.5967407,4.4271199 2.5476481,4.5986857 c -0.03241,0.112456 0.033276,0.2295855 0.1457275,0.2619995 0.112457,0.03241 0.2295855,-0.033276 0.2619995,-0.1457276 z'
    } )
  );

  // Copy blocks with new client IDs by serializing and parsing them again.
  // This keeps every block's saved content (including Custom HTML) intact.
  function copyBlocks( blocksArray ) {
    return blocks.parse( blocks.serialize( blocksArray ) );
  }

  // Replace a wrapper with its inner blocks.
  function unwrapBlock( clientId ) {
    var block = select( 'core/block-editor' ).getBlock( clientId );
    if ( ! block ) return;
    dispatch( 'core/block-editor' ).replaceBlocks( clientId, block.innerBlocks );
  }

  // Wrap the current selection (one or many blocks) in a bs Wrapper.
  function wrapSelection() {
    var store = select( 'core/block-editor' );
    var ids = store.getSelectedBlockClientIds();
    if ( ! ids.length ) return;
    var selected = store.getBlocksByClientId( ids );
    var wrapper = createBlock( 'bootscore/wrapper', {}, copyBlocks( selected ) );
    dispatch( 'core/block-editor' ).replaceBlocks( ids, wrapper );
  }

  blocks.registerBlockType( 'bootscore/wrapper', {
    apiVersion: 2,
    title: __( 'Wrap', 'bootscore' ),
    description: __( 'A flexible semantic wrapper block. Select any HTML tag, add classes and custom attributes - no layout bloat.', 'bootscore' ),
    icon: wrapIcon,
    category: 'layout',

    attributes: {
      tagName: {
        type: 'string',
        default: 'div'
      },
      cssClass: {
        type: 'string',
        default: ''
      },
      extraAttributes: {
        type: 'array',
        default: [],
        items: {
          type: 'object',
          properties: {
            key:   { type: 'string' },
            value: { type: 'string' }
          }
        }
      }
    },

    transforms: {
      from: [
        {
          type: 'block',
          isMultiBlock: true,
          blocks: [ '*' ],
          __experimentalConvert: function ( blocksArray ) {
            return createBlock( 'bootscore/wrapper', {}, copyBlocks( blocksArray ) );
          }
        }
      ]
    },

    edit: function ( props ) {
      var attributes = props.attributes;
      var setAttributes = props.setAttributes;
      var clientId = props.clientId;
      var extraAttributes = attributes.extraAttributes || [];

      var blockProps = useBlockProps( {
        className: attributes.cssClass || ''
      } );

      var innerBlocksProps = useInnerBlocksProps( blockProps );

      function updateAttr( index, field, value ) {
        var updated = extraAttributes.map( function ( item, i ) {
          if ( i !== index ) return item;
          var newItem = { key: item.key, value: item.value };
          newItem[ field ] = value;
          return newItem;
        } );
        setAttributes( { extraAttributes: updated } );
      }

      function addAttr() {
        setAttributes( {
          extraAttributes: extraAttributes.concat( [ { key: '', value: '' } ] )
        } );
      }

      function removeAttr( index ) {
        setAttributes( {
          extraAttributes: extraAttributes.filter( function ( _, i ) {
            return i !== index;
          } )
        } );
      }

      return el(
        element.Fragment,
        {},

        // Toolbar: Unwrap button
        el(
          BlockControls,
          {},
          el(
            ToolbarGroup,
            {},
            el( ToolbarButton, {
              icon: unwrapIcon,
              label: __( 'Unwrap', 'bootscore' ),
              onClick: function () {
                unwrapBlock( clientId );
              }
            } )
          )
        ),

        // Sidebar
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __( 'Wrapper Settings', 'bootscore' ), initialOpen: true },
            el( SelectControl, {
              label: __( 'HTML Tag', 'bootscore' ),
              value: attributes.tagName,
              options: TAG_OPTIONS,
              onChange: function ( value ) {
                setAttributes( { tagName: value } );
              }
            } ),
            el( TextControl, {
              label: __( 'CSS Class', 'bootscore' ),
              value: attributes.cssClass,
              onChange: function ( value ) {
                setAttributes( { cssClass: value } );
              }
            } )
          ),
          el(
            PanelBody,
            { title: __( 'HTML Attributes', 'bootscore' ), initialOpen: true },
            extraAttributes.map( function ( attr, index ) {
              return el(
                'div',
                {
                  key: index,
                  style: { display: 'flex', gap: '8px', alignItems: 'flex-end', marginBottom: '8px' }
                },
                el(
                  'div',
                  { style: { flex: 1 } },
                  el( TextControl, {
                    label: index === 0 ? __( 'Attribute', 'bootscore' ) : undefined,
                    placeholder: 'id',
                    value: attr.key,
                    onChange: function ( value ) {
                      updateAttr( index, 'key', value );
                    }
                  } )
                ),
                el(
                  'div',
                  { style: { flex: 1 } },
                  el( TextControl, {
                    label: index === 0 ? __( 'Value', 'bootscore' ) : undefined,
                    placeholder: 'my-id',
                    value: attr.value,
                    onChange: function ( value ) {
                      updateAttr( index, 'value', value );
                    }
                  } )
                ),
                el( Button, {
                  icon: 'trash',
                  isSmall: true,
                  isDestructive: true,
                  style: { marginBottom: index === 0 ? '8px' : '0' },
                  label: __( 'Remove', 'bootscore' ),
                  onClick: function () {
                    removeAttr( index );
                  }
                } )
              );
            } ),
            el(
              Button,
              {
                variant: 'secondary',
                isSmall: true,
                onClick: addAttr
              },
              __( '+ Add Attribute', 'bootscore' )
            )
          )
        ),

        // Editor canvas — useInnerBlocksProps merges InnerBlocks directly
        // onto the block wrapper element, eliminating intermediate divs.
        el( 'div', innerBlocksProps )
      );
    },

    save: function () {
      return el( InnerBlocks.Content );
    }
  } );

  // Three-dot options menu: "Wrap" for any selection, "Unwrap" for wrappers.
  if ( PluginBlockSettingsMenuItem ) {
    registerPlugin( 'bootscore-wrapper-menu', {
      render: function () {
        return el(
          element.Fragment,
          {},
          el( PluginBlockSettingsMenuItem, {
            icon: wrapIcon,
            label: __( 'Wrap', 'bootscore' ),
            onClick: wrapSelection
          } ),
          el( PluginBlockSettingsMenuItem, {
            allowedBlocks: [ 'bootscore/wrapper' ],
            icon: unwrapIcon,
            label: __( 'Unwrap', 'bootscore' ),
            onClick: function () {
              var id = select( 'core/block-editor' ).getSelectedBlockClientId();
              if ( id ) unwrapBlock( id );
            }
          } )
        );
      }
    } );
  }
} )(
  window.wp.blocks,
  window.wp.element,
  window.wp.blockEditor,
  window.wp.components,
  window.wp.i18n,
  window.wp.data,
  window.wp.plugins,
  window.wp.editor
);
