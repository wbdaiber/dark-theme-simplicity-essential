(function() {
    const { addFilter } = wp.hooks;
    const { createHigherOrderComponent } = wp.compose;
    
    /**
     * Add custom class to all blocks to ensure empty blocks are always visible and have appropriate height.
     */
    const addExtraPropsToBlocks = createHigherOrderComponent((BlockListBlock) => {
        return (props) => {
            // Add custom class to each block
            if (props.attributes && typeof props.attributes.className !== 'undefined') {
                props.attributes.className = `dts-block ${props.attributes.className}`;
            } else if (props.attributes) {
                props.attributes.className = 'dts-block';
            }
            
            return <BlockListBlock {...props} />;
        };
    }, 'addExtraPropsToBlocks');
    
    addFilter(
        'editor.BlockListBlock',
        'dark-theme-simplicity/add-extra-block-props',
        addExtraPropsToBlocks
    );
    
    /**
     * Enhance keyboard behavior for better paragraph handling
     */
    wp.domReady(function() {
        // Add event listener to handle Enter key in empty paragraphs
        document.addEventListener('keydown', function(event) {
            // Check if we're in the editor
            if (!document.querySelector('.editor-styles-wrapper')) {
                return;
            }
            
            // Handle Enter key
            if (event.key === 'Enter') {
                const selection = window.getSelection();
                if (!selection || selection.rangeCount === 0) return;
                
                const range = selection.getRangeAt(0);
                const startContainer = range.startContainer;
                
                // Check if we're in an empty paragraph
                if (startContainer && 
                    startContainer.nodeType === Node.ELEMENT_NODE &&
                    startContainer.classList.contains('block-editor-rich-text__editable') &&
                    (startContainer.textContent.trim() === '' || startContainer.innerHTML.trim() === '<br>')) {
                    
                    // Prevent the default behavior which sometimes stays in same block
                    event.preventDefault();
                    
                    // Programmatically create a new block
                    if (wp.data && wp.data.dispatch) {
                        const { insertBlock } = wp.data.dispatch('core/block-editor');
                        const { createBlock } = wp.blocks;
                        
                        // Create a new paragraph block
                        const newBlock = createBlock('core/paragraph', { content: '' });
                        
                        // Insert the new block
                        insertBlock(newBlock);
                    }
                }
            }
        });
    });
})(); 