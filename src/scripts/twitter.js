$(function() {

  // $.get( 'https://platform.twitter.com/widgets.js' );

    const tweets = document.querySelectorAll( 'blockquote.twitter-tweet' );

    for ( const tweet of tweets ) {
        const anchors = tweet.querySelectorAll( 'a' );
        const url = anchors[anchors.length - 1].href;

        /* TODO: temporary fix */

        let jq = jQuery || $;

        jq.ajax( {
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'ftf_embed_tweet',
                tweet_id: url.match(/(\d+)/g)[0]
            },
            type: 'POST',
            success: function( data ) {
                // console.log( 'response', data );

                if ( data ){
                    let text = data.full_text,
                        entities = null,
                        renderedTweetHTML = `<div class="card h-100 w-100">
                            <div class="card-body pt-4">
                                <div class="card-text">
                                <p>
                                    <a href="https://twitter.com/${ data.user.screen_name }"><img class="rounded-circle" style="width: 48px; height: 48px" src="${ data.user.profile_image_url_https }"></a>
                                    <strong class="ml-2"><a href="https://twitter.com/${ data.user.screen_name }">${ data.user.name }</a></strong>
                                </p>`;

                    if ( data.extended_entities ){
                        entities = data.extended_entities;
                    } else if ( data.entities ){
                        entities = data.entities;
                    }

                    if ( entities ){

                        if ( entities.media ){
                            entities.media.forEach( function( media ){

                                if ( media.video_info && media.video_info.variants && media.video_info.variants.length ){
                                    let videoUrl = '';

                                    for (let i = 0; i < media.video_info.variants.length ; i++ ) {
                                        if ( media.video_info.variants[i].content_type === 'video/mp4' ){
                                            videoUrl = media.video_info.variants[i].url;
                                            break;
                                        }
                                    }

                                    text = text.replace( data.full_text.substring( media.indices[0], media.indices[1] ), `<video width="320" height="240" controls><source src="${ videoUrl }" type="video/mp4"></video>` );
                                    
                                } else if ( media.media_url_https ){

                                    text = text.replace( data.full_text.substring( media.indices[0], media.indices[1] ), `<p><a href="${ url }" target="_blank"><img class="w-100 mt-3" src="${ media.media_url_https }"></p>` );

                                }

                            } );
                        }

                        if ( entities.urls ){

                            entities.urls.forEach( function( url ){
                                text = text.replace( data.full_text.substring( url.indices[0], url.indices[1] ), '<a href="' + url.expanded_url + '" target="_blank">' + url.display_url + '</a>' );

                            } );

                        }

                        if ( entities.user_mentions ){

                            entities.user_mentions.forEach( function( user_mention ){
                                text = text.replace( data.full_text.substring( user_mention.indices[0], user_mention.indices[1] ), '<a href="https://twitter.com/' + user_mention.screen_name + '" target="_blank">@' + user_mention.screen_name + '</a>' );


                            } );
                            
                        }

                        if ( entities.hashtags ){

                            entities.hashtags.forEach( function( hashtag ){
                                text = text.replace( data.full_text.substring( hashtag.indices[0], hashtag.indices[1] ), '<a href="https://twitter.com/hashtag/' + hashtag.text + '" target="_blank">#' + hashtag.text + '</a>' );

                            } );
                            
                        }

                    }

                    const tweetDate = new Date( data.created_at ).toLocaleDateString( navigator.language, { month: 'long', year: 'numeric', day: 'numeric' } );

                    renderedTweetHTML += text + `</p>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                                🔁 <a href="${ url }" target="_blank">${ data.retweet_count }</a> |
                                ❤️ <a href="${ url }" target="_blank">${ data.favorite_count }</a> |
                                <a href="${ url }" target="_blank">${ tweetDate }</a>
                        </div>
                    </div>`;
                    
                    let renderedTweet = document.createElement( 'div' );
                    renderedTweet.className = 'twitter-tweet twitter-tweet-rendered w-100'
                    renderedTweet.innerHTML = renderedTweetHTML;
                    tweet.parentNode.replaceChild( renderedTweet, tweet );
                    console.log( renderedTweet, entities, data );
                }
            }
        } );
    }
});
