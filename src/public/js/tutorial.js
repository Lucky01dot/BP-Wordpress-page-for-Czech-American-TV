let gt_yt_player;

//#region FUNCTIONS

/**
 * The function name is strictly set by the YT API (www.youtube.com/iframe_api)
 * Once the video is ready, define the function with custom functionality.
 */
function onYouTubeIframeAPIReady() {
    gt_yt_player = new YT.Player(GT_SELECTOR.GT_TUTORIAL_VIDEO.substring(1) /* ID */, {
        events: {
            'onReady': on_player_ready
        }
    });
}

/**
 * Custom functionality about YT video
 *
 * The buttons that are clicked require `data-time` attribute to seek the video afterwards.
 */
function on_player_ready(event) {
    // Seek BTNs
    $(GT_SELECTOR.GT_TUTORIAL_SEEK_BTNS).on("click", function () {
        let time = $(this).data("time");
        gt_yt_player.seekTo(time, true);
    });
}

//#endregion
