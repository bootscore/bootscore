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
      d: 'm 1.6626031,1.0583333 c -0.3312016,0 -0.6042698,0.2730644 -0.6042698,0.6042698 v 3.0247938 c 0,0.3312054 0.2730682,0.6042697 0.6042698,0.6042697 H 1.8648302 V 4.8885904 H 1.6626031 c -0.1148256,0 -0.2011936,-0.086372 -0.2011936,-0.2011935 V 1.6626031 c 0,-0.1148214 0.086365,-0.2011936 0.2011936,-0.2011936 H 1.8644857 V 1.0583333 Z m 2.8229112,0 v 0.4030762 h 0.2018826 c 0.1148193,0 0.2011935,0.086372 0.2011935,0.2011936 v 3.0247938 c 0,0.1148214 -0.086378,0.2011935 -0.2011935,0.2011935 H 4.4855143 v 0.4030762 h 0.2018826 c 0.3312097,0 0.6042697,-0.2730643 0.6042697,-0.6042697 V 1.6626031 c 0,-0.3312054 -0.27306,-0.6042698 -0.6042697,-0.6042698 z m -2.217608,0.8061524 -0.2015381,3.445e-4 a 0.20158976,0.20158976 0 0 0 -0.201538,0.201538 V 2.2679063 2.9734619 3.376538 3.8805555 H 2.2679063 V 3.376538 h 0.2015381 0.252181 0.00207 1.0056234 c 0.1263728,0 0.242052,0.066965 0.3052355,0.176389 0.031579,0.054704 0.047542,0.1155515 0.047542,0.1763888 V 3.8805555 H 4.4851698 V 3.7293158 C 4.4851762,3.5987184 4.4513357,3.4682048 4.383884,3.3513888 4.2490039,3.1177781 3.9990764,2.9734619 3.7293158,2.9734619 H 3.3896294 C 3.5104603,2.7445178 3.5059364,2.4680584 3.375849,2.2427571 3.2409623,2.0091464 2.9910277,1.8648302 2.7212809,1.8648302 v -3.445e-4 z m 0,0.4034206 h 0.4537191 c 0.1263725,0 0.2424033,0.06696 0.30558,0.1763889 0.063163,0.1094241 0.063163,0.2433484 0,0.3527778 -0.063163,0.109424 -0.1792075,0.1763889 -0.30558,0.1763889 H 2.2679063 Z M 1.8648302,4.0820936 v 0.2015381 a 0.20158976,0.20158976 0 0 0 0.201538,0.2018826 H 2.2679063 V 4.0820936 Z m 0.6046142,0 V 4.4855143 H 4.0824381 4.2839762 A 0.20158976,0.20158976 0 0 0 4.4855143,4.2836317 V 4.0820936 H 4.0824381 Z'
    } )
  );

  // Unwrap icon (toolbar button + "Unwrap" menu item)
  var unwrapIcon = el( 'svg', {
    xmlns: 'http://www.w3.org/2000/svg',
    viewBox: '0 0 6.35 6.35',
    fill: 'currentColor'
  },
    el( 'path', {
      d: 'm 1.6626031,1.0583333 c -0.3312016,0 -0.6042698,0.2730644 -0.6042698,0.6042698 v 3.0247938 c 0,0.010832 4.57e-4,0.021684 0.00103,0.032384 L 1.4614095,4.3177382 V 1.6626031 c 0,-0.1148214 0.086365,-0.2011936 0.2011936,-0.2011936 H 1.8644857 V 1.0583333 Z m 2.8229112,0 V 1.2936334 L 4.7197808,1.0593669 c -0.0107,-5.766e-4 -0.021552,-0.00103 -0.032384,-0.00103 z M 4.9481906,1.1169 1.1169,4.9481906 C 1.17631,5.0729229 1.2770771,5.1736898 1.4018094,5.2331 L 5.2331,1.4018094 C 5.1736898,1.2770771 5.0729229,1.1763102 4.9481906,1.1169 Z M 5.2906331,1.6284966 4.8885904,2.0301948 v 2.6572021 c 0,0.1148214 -0.086378,0.2011935 -0.2011935,0.2011935 H 4.4855143 v 0.4030762 h 0.2018826 c 0.3312097,0 0.6042697,-0.2730643 0.6042697,-0.6042697 V 1.6626031 c 0,-0.011471 -3.873e-4,-0.022784 -0.00103,-0.034106 z M 2.2679063,1.8644857 2.0663682,1.8648302 a 0.20158976,0.20158976 0 0 0 -0.201538,0.201538 V 2.2679063 2.9734619 3.376538 3.8805555 h 0.033762 L 2.2679063,3.5112413 V 3.376538 H 2.4026096 L 2.8194661,2.9596815 c -0.031383,0.00903 -0.064189,0.01378 -0.097841,0.01378 H 2.2679063 V 2.2679063 h 0.4537191 c 0.1263725,0 0.2424033,0.06696 0.30558,0.1763889 0.04869,0.084352 0.059795,0.1832034 0.033417,0.2742296 L 3.4275254,2.3516222 C 3.4133353,2.3143252 3.3961606,2.2779352 3.375849,2.2427571 3.2409623,2.0091464 2.9910277,1.8648302 2.7212809,1.8648302 V 1.8644857 Z M 3.9205186,2.9982666 3.5422472,3.376538 h 0.1870686 c 0.1263728,0 0.242052,0.066965 0.3052355,0.176389 0.031579,0.054704 0.047542,0.1155515 0.047542,0.1763888 V 3.8805555 H 4.4851698 V 3.7293158 C 4.4851762,3.5987184 4.4513357,3.4682048 4.383884,3.3513888 4.2817477,3.17449 4.1135951,3.0488701 3.9208632,2.9982666 Z m -1.083827,1.083827 -0.3672472,0.3672472 v 0.036174 H 4.0824381 4.2839762 A 0.20158976,0.20158976 0 0 0 4.4855143,4.2836317 V 4.0820936 H 4.0824381 Z M 1.8648302,5.053955 1.6284966,5.2906331 c 0.011323,6.462e-4 0.022635,0.00103 0.034106,0.00103 h 0.2022272 z'
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
