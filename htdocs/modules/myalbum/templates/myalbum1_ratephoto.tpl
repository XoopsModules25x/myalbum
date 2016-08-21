<{strip}>
    <table width="100%" cellspacing="0" class="outer">
        <tr>
            <td class="even">
                <br/>
                <{include file="db:myalbum1_header.tpl"}>
                <br/>
                <hr/>
                <div align="center">

                    <table border="0" cellpadding="1" cellspacing="0" width="80%" align="center">
                        <tr>
                            <td align="center">
                                <img src="<{$photo.imgsrc_thumb}>" border="0" <{$photo.width_spec}> /><br/>
                                <h4><{$photo.title}></h4>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <ul>
                                    <li><{$lang_voteonce}>
                                    <li><{$lang_ratingscale}>
                                    <li><{$lang_beobjective}>
                                    <li><{$lang_donotvote}>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <form method="post" action="ratephoto.php?lid=<{$photo.lid}>">
                                    <input type="hidden" name="lid" value="<{$photo.lid}>"/>
                                    <select name="rating">
                                        <option>--</option>
                                        <option>10</option>
                                        <option>9</option>
                                        <option>8</option>
                                        <option>7</option>
                                        <option>6</option>
                                        <option>5</option>
                                        <option>4</option>
                                        <option>3</option>
                                        <option>2</option>
                                        <option>1</option>
                                    </select>&nbsp;&nbsp;
                                    <input type="submit" name="submit" value="<{$lang_rateit}>"/>
                                    <input type=button value="<{$lang_cancel}>" onclick="history.back();"/>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <br/>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center">
                <{include file="db:myalbum1_footer.tpl"}>
            </td>
        </tr>
    </table>
<{/strip}>
