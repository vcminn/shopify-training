{% form 'product', product, id:form_id %}

{% comment %}
Add product variants as a dropdown.
- By default, each variant (or combination of variants) will display as its own <option>
    - To separate these into multiple steps, which we suggest, use option_selection.js (see below)

    You can leverage jQuery to add a callback on page load and each time the select element changes:
    - Include option_selection.js (as seen at the bottom of this file)
    - This allows you to use JavaScript anytime the variant dropdown changes
    - This also separates out your variant options (ie. size, color, etc.) to separate select elements

    For more information on products with multiple options, visit:
    - http://docs.shopify.com/support/your-website/themes/can-i-make-my-theme-use-products-with-multiple-options#update-product-liquid
    {% endcomment %}
    <div class="product-single__variants">
        <select name="id" id="ProductSelect-{{ section.id }}" class="product-single__variants">
            {% for variant in product.variants %}
            {%- include 'bold-variant' with variant, hide_action: 'skip' -%}
            {% if variant.available %}

            {% comment %}
            Note: if you use option_selection.js, your <select> tag will be overwritten, meaning what you have inside <option> will not reflect what you coded below.
    {% endcomment %}
<option {% if variant == bold_selected_or_first_available_variant %} selected="selected" {% endif %} data-sku="{{ variant.sku }}" value="{{ variant.id }}">{{ variant.title }} - {{ bold_variant_price | money_with_currency }}</option>

{% else %}
<option disabled="disabled">
    {{ variant.title }} - {{ 'products.product.sold_out' | t }}
</option>
{% endif %}
{% endfor %}
</select>
</div>

<div class="grid--uniform product-single__addtocart{% if section.settings.enable_payment_button %} product-single__shopify-payment-btn{% endif %}">
    {% if section.settings.product_qty_enable %}
    {% unless sold_out %}<label>{{ 'products.product.quantity' | t }}</label>{% endunless %}
    <input type="number" id="quantity" name="quantity" value="1" min="1" class="quantity-selector">
    {% endif %}
    <button type="submit" name="add" id="addToCart-{{ section.id }}" class="btn btn--large btn--full{% if section.settings.enable_payment_button %} shopify-payment-btn btn--secondary{% endif %}">
        <span class="add-to-cart-text">{{ 'products.product.add_to_cart' | t }}</span>
    </button>
    {% if section.settings.enable_payment_button %}
    {{ form | payment_button }}
    {% endif %}
</div>

{% endform %}
