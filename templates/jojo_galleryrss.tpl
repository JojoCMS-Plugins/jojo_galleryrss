<h2>{$feed->get_feed_title()}</h2>

{foreach from=$images key=id item=imgsrc}
    <div style="float: left; width: 120px;">
        <a href="{$items.$id->get_link()}"><img src="{$imgsrc}" alt="{$items.$id->get_title()}" width='100px' /></a>
        {$items.$id->get_title()}
    </div>
{/foreach}

<br style='clear:both'/>