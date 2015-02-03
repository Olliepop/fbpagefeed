<% loop $FBPageFeed %>
    <article class="fb-page-post">
        <div class="post-image">
            <img src="{$ImageSource}" />
        </div>
        <div class="time-posted">
            <a href="{$URL}" target="_blank">{$TimePosted}</a>
        </div>
        <div class="post-content">
            {$Content}
        </div>
    </article>
<% end_loop %>