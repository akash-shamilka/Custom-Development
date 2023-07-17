jQuery(document).ready(function($) {
    var nonce = post_load_params.nonce;
    var page = 1; // current page
    var postsPerPage = 6; // posts per page
    var maxPages = post_load_params.max_pages;
    var loading = false; // loading flag

    $('#load-more-btn').click(function() {
        if (page < maxPages && !loading) {
            loading = true;
            page++;

            $.ajax({
                url: post_load.ajaxurl,
                type: 'post',
                data: {
                    action: 'post_load',
                    page: page,
                    postsPerPage: postsPerPage,
                    nonce: nonce,
                },
                beforeSend: function() {
                    $('#load-more-btn').hide();
                    $('#loadinggif-image').fadeIn();
                },
                success: function(data) {
                    $('.custom-posts-wrapper-blog').append(data);
                    loading = false;
                    // check if all posts have been loaded
                    if (page == maxPages) {
                        // hide the load more button and loading GIF
                        $('#load-more-btn').hide();
                        $('#loadinggif-image').hide();
                        // append a message to the container for the posts
                        $('.append-post-message').append('<p class="post-message">All posts have been loaded.</p>');
                    } else {
                        // show the load more button
                        $('#load-more-btn').show();
                    }
                },
                error: function() {
                    // display error message
                    loading = false;
                },
                complete: function() {
                    // hide the loading GIF
                    $('#loadinggif-image').hide();
                }
            });
        }
    });
});
