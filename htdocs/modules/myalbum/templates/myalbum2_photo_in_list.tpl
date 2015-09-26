<tr>
    <{strip}>

    <{* IMAGE PART *}>
    <td width='150' align='center'>
        <br/>

        <div align='center'>
            <{if $photo.is_normal_image }>

            <{* NORMAL IMAGES *}>
            <a href='<{$mod_url}>/photo.php?lid=<{$photo.lid}>'><img src='<{$photo.imgsrc_thumb}>' <{$photo.width_spec}> alt='<{$photo.title}>'
                title='<{$photo.title}>' /></a>

            <{else}>

            <{* FILES OTHER THAN IMAGES *}>
            <a href='<{$photo.ahref_photo}>' target='_blank'><img src='<{$photo.imgsrc_thumb}>' <{$photo.width_spec}> alt='<{$photo.title}>'
                title='<{$photo.title}>' /></a>

            <{/if}>
        </div>
        <br/>
    </td>

    <{* INFORMATION PART *}>
    <td align='left'>

        <{* 1ST LINE OF INFORMATION *}>

        <{* EDIT ICON *}>
        <{if $photo.can_edit}>
        <a href='<{$mod_url}>/editphoto.php?lid=<{$photo.lid}>'><img src='<{xoModuleIcons16 edit.png}>' border='0' alt='<{$lang_editthisphoto}>'
                                                                     title='<{$lang_editthisphoto}>'/></a>
        <{/if}>

        <{* PHOTO'S SUBJECT *}>
        <a name='<{$photo.lid}>'></a><a href='<{$mod_url}>/photo.php?lid=<{$photo.lid}>&amp;cid=<{$photo.cid}>'><strong><{$photo.title}></strong></a>

        <{* NEW ICON *}>
        <{if $photo.is_newphoto}>
        <img src='<{$mod_url}>/assets/images/newred.gif' border='0' alt='<{$lang_new}>' title='<{$lang_new}>'/>
        <{/if}>

        <{* UPDATE ICON *}>
        <{if $photo.is_updatedphoto}>
        <img src='<{$mod_url}>/assets/images/update.gif' border='0' alt='<{$lang_updated}>' title='<{$lang_updated}>'/>
        <{/if}>

        <{* POPULAR ICON *}>
        <{if $photo.is_popularphoto}>
        <img src='<{$mod_url}>/assets/images/pop.gif' border='0' alt='<{$lang_popular}>' title='<{$lang_popular}>'/>
        <{/if}>
        <br/>


        <{* 2ND LINE OF INFORMATION *}>

        <{* SUBMITTER *}>
        <{if $photo.submitter > 0}>
        <a href='<{$xoops_url}>/userinfo.php?uid=<{$photo.submitter}>'>
            <img src='<{$mod_url}>/assets/images/submitter.gif' width='16' height='16' alt='<{$lang_submitter}>' title='<{$lang_submitter}>' border='0'/>
        </a>
        <{$photo.submitter_name}>
        <a href='<{$mod_url}>/viewcat.php?uid=<{$photo.submitter}>'><img src='<{$mod_url}>/assets/images/myphotos.gif' width='16' height='16'
                                                                         alt='<{$photo.info_morephotos}>' title='<{$photo.info_morephotos}>'
                                                                         border='0'/></a>
        <{else}>
        <img src='<{$mod_url}>/assets/images/submitter.gif' width='16' height='16' alt='<{$lang_submitter}>' title='<{$lang_submitter}>'/>
        <{$photo.submitter_name}>
        <{/if}>
        &nbsp; &nbsp;

        <{* CATEGORY *}>
        <{if $photo.cat_title}>
        <a href='<{$mod_url}>/viewcat.php?cid=<{$photo.cid}>'><img src='<{xoModuleIcons16 topic.png}>' width='16' height='16' alt='<{$lang_category}>'
                                                                   title='<{$lang_category}>' border='0'/><{$photo.cat_title}></a> &nbsp; &nbsp;
        <{/if}>

        <{* LAST UPDATED *}>
        <img src='<{$mod_url}>/assets/images/clock.gif' width='16' height='16' alt='<{$lang_lastupdatec}>' title='<{$lang_lastupdatec}>'/><{$photo.datetime}>

        <br/>


        <{* 3RD LINE OF INFORMATION *}>

        <{* HIT COUNTS *}>
        <img src='<{$mod_url}>/assets/images/hits.gif' width='16' height='16' alt='<{$lang_hitsc}>' title='<{$lang_hitsc}>'/><{$photo.hits}> &nbsp;

        <{* COMMENT COUNTS *}>
        <img src='<{$mod_url}>/assets/images/comments.gif' width='16' height='16' alt='<{$lang_commentsc}>' title='<{$lang_commentsc}>'/><{$photo.comments}>
        &nbsp; &nbsp;

        <{* RANK & RATING *}>
        <{if $canrateview}>
        <{if $photo.rating > 0}>
        <img src='<{$mod_url}>/assets/images/rank<{$photo.rank}>.gif' alt='<{$photo.rating}>' title='<{$photo.rating}>' border='0'/><{$photo.info_votes}>
        <{else}>
        <img src='<{$mod_url}>/assets/images/rank_none.gif' alt='<{$photo.info_votes}>' title='<{$photo.info_votes}>' border='0'/><{$photo.info_votes}>
        <{/if}>
        <{/if}>

        <{* VOTE BUTTON *}>
        <{if $canratevote}>
        <a href='<{$mod_url}>/ratephoto.php?lid=<{$photo.lid}>'><img src='<{$mod_url}>/assets/images/vote.gif' alt='<{$lang_ratethisphoto}>'
                                                                     title='<{$lang_ratethisphoto}>' border='0'/><{$lang_ratethisphoto}></a>
        <{/if}>

        <br/>


        <{* DESCRIPTION *}>

        <{if $photo.description || $photo.tagbar}>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' class='outer'>
            <{if $photo.description}>
            <tr>
                <td class='odd'>
                    <{$photo.description}>
                </td>
            </tr>
            <{/if}>
            <{if $photo.tagbar}>
            <tr>
                <td class='even'>
                    <{include file="db:tag_bar.html" tagbar=$photo.tagbar}>
                </td>
            </tr>
            <{/if}>
        </table>
        <{/if}>


    </td>
    <{/strip}>
</tr>
