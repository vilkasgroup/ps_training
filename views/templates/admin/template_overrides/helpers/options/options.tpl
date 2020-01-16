
{extends file="helpers/options/options.tpl"}

{block name="input" append}
    {if $field['type'] == 'custom_select'}
        <div class="col-lg-9">
            {foreach $field['choices'] AS $k => $v}
                <p class="checkbox">
                    {strip}
                        <label class="col-lg-3" for="{$key}{$k}_on">
                            <input type="checkbox" name="{$key}{$k}" id="{$key}{$k}_on" value="{$k|intval}"{if $k == $select_fields_values} checked="checked"{/if}{if isset($field['js'][$k])} {$field['js'][$k]}{/if}/>
                            {$v}
                        </label>
                    {/strip}
                </p>
            {/foreach}
        </div>
    {/if}
{/block}
