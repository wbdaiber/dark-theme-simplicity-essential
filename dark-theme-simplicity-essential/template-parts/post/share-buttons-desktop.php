<?php
/**
 * Desktop Share Buttons
 * 
 * @package Dark_Theme_Simplicity
 */
?>

<div class="p-1.5 rounded-lg">
<div class="flex flex-col gap-1">
        <!-- Share Dropdown -->
        <div class="relative w-full" id="sidebar-share-container">
            <button class="flex items-center gap-2 bg-dark-300/80 hover:bg-dark-300 border border-white/5 rounded-lg px-2 py-1 text-xs text-white transition-all w-full" id="sidebar-share-btn" aria-expanded="false" aria-controls="sidebar-share-dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-300">
                    <circle cx="18" cy="5" r="3"></circle>
                    <circle cx="6" cy="12" r="3"></circle>
                    <circle cx="18" cy="19" r="3"></circle>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                </svg>
                <span><?php esc_html_e( 'Share', 'dark-theme-simplicity' ); ?></span>
            </button>
            
            <!-- Desktop Share Dropdown -->
            <div class="hidden absolute top-full mt-2 right-0 rounded-lg shadow-2xl py-2 z-[9999] overflow-y-auto desktop-share-dropdown" id="sidebar-share-dropdown" role="menu" aria-labelledby="sidebar-share-btn" style="min-width: 180px; max-height: 400px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(139, 92, 246, 0.18) 100%), #0c0c0e; border: 1px solid rgba(139, 92, 246, 0.25);">
                <div class="flex flex-col">
                    <!-- Facebook Share -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Share on Facebook (opens in new window)"
                       class="flex items-center gap-2 px-3 py-2 hover:bg-dark-300/80 text-white text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-[#1877F2] flex-shrink-0">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span>Facebook</span>
                    </a>
                    
                    <!-- X (Twitter) Share -->
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Share on X (opens in new window)"
                       class="flex items-center gap-2 px-3 py-2 hover:bg-dark-300/80 text-white text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-white flex-shrink-0">
                            <path d="M18.244 2.25h3.308l-7.227 8.259 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        <span>X</span>
                    </a>
                    
                    <!-- LinkedIn Share -->
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Share on LinkedIn (opens in new window)"
                       class="flex items-center gap-2 px-3 py-2 hover:bg-dark-300/80 text-white text-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="text-[#0A66C2] flex-shrink-0">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        <span>LinkedIn</span>
                    </a>
                    
                    <!-- Copy Link Option -->
                    <button onclick="copyToClipboard('<?php echo esc_js( get_permalink() ); ?>')"
                            aria-label="Copy link to clipboard"
                            class="flex items-center gap-2 px-3 py-2 bg-transparent hover:bg-dark-300/80 text-white text-xs transition-colors w-full text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-300 flex-shrink-0">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                        <span><?php esc_html_e( 'Copy Link', 'dark-theme-simplicity' ); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>