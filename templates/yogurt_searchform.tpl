<{ if $isanonym!=1 }>
<{include file="db:yogurt_navbar.tpl"}>
<{/if}>

<{$searchform.javascript}>
<h2><{$searchform.title}></h2>
<form name="<{$searchform.name}>" action="<{$searchform.action}>" method="<{$searchform.method}>" <{$searchform.extra}>>
  <div>
    <!-- start of form elements loop -->
    <{foreach item=element from=$searchform.elements}>
      <{if $element.hidden !== true}>
      <div class="search-element">
        <div class="search-element-label"><label><{$element.caption}></label></div>
        <div class="search-element-body"><{$element.body}></div>
      </div>
      <{else}>
      <{$element.body}>
      <{/if}>
    <{/foreach}>
    <!-- end of form elements loop -->
  </div>
</form>

<{include file="db:yogurt_footer.tpl"}>
