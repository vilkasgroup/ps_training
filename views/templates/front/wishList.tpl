{extends file=$layout}

{block content}
  <h1 class="wishlist-header">{l s='Wish list' mod='training'}</h1>
  <div class="row">
      {foreach from=$products item='product' key='position'}
        <div class="col-md-3">
          {include file='catalog/_partials/miniatures/product.tpl' product=$product position=$position}

        </div>
      {/foreach}
  </div>
{/block}
