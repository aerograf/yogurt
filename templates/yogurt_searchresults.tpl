<{include file="db:yogurt_navbar.tpl"}>


<{if $total_found != 0}>
<table class="outer" cellspacing="1" cellpadding="4">
  <tr>
    <th align="center"><{$lang_avatar}></th><th align="center"><{$lang_username}></th><th align="center"><{$lang_realname}></th> </tr>
  <{section name=i loop=$users}>
  <tr valign="middle">
    <td class="even"><{$users[i].avatar}></td><td class="odd"><a href="index.php?uid=<{$users[i].id}>"><{$users[i].name}></a><br><{if $is_admin === true}><{$users[i].adminlink}><{/if}></td><td class="even"><{$users[i].realname}></td>  </tr>
  <{/section}>
</table>
<div style="text-align:center">
  <{$pagenav}>
  <{$lang_numfound}>
</div>
<{else}>
  <{$lang_nonefound}>
<{/if}>

<{include file="db:yogurt_footer.tpl"}>
