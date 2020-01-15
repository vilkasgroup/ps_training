{extends file=$layout}

{block name='content'}
    <button class="btn btn-primary what-day-button">What day is today?</button>
    <div class="row">
        {foreach from=$products item="product" key="position"}
            <div class="col-lg-4">
                {include file="catalog/_partials/miniatures/product.tpl" product=$product position=$position}
            </div>
        {/foreach}
    </div>


{/block}
