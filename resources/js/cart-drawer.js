(function() {
    const initCartHandlers = () => {
        if (!window.jQuery) {
            setTimeout(initCartHandlers, 10);
            return;
        }

        const $ = window.jQuery;

        // 1. Zdarzenie dodania do koszyka (AJAX z kafelków na liście produktów)
        $(document.body).on('added_to_cart', function() {
            window.dispatchEvent(new CustomEvent('cart-open'));
        });

        // 2. Przechwycenie dodawania na pojedynczej karcie produktu
        $(document).on('submit', 'form.cart', function(e) {
            const $form = $(this);

            if ($form.closest('.product').hasClass('product-type-external')) {
                return;
            }

            e.preventDefault();

            const $button = $form.find('.single_add_to_cart_button');
            $button.addClass('loading').css('opacity', '0.7');

            let data = $form.serialize();
            if (!data.includes('add-to-cart=')) {
                const productId = $form.find('[name="add-to-cart"]').val() || $button.val();
                data += '&add-to-cart=' + productId;
            }

            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: data,
                success: function() {
                    $.ajax({
                        url: '/?wc-ajax=get_refreshed_fragments',
                        type: 'POST',
                        success: function(response) {
                            if (response && response.fragments) {
                                $.each(response.fragments, function(key, value) {
                                    $(key).replaceWith(value);
                                });
                            }
                            window.dispatchEvent(new CustomEvent('cart-open'));
                        }
                    });
                },
                error: function(err) {
                    console.error('Błąd dodawania do koszyka AJAX:', err);
                    window.location.reload();
                },
                complete: function() {
                    $button.removeClass('loading').css('opacity', '');
                }
            });
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCartHandlers);
    } else {
        initCartHandlers();
    }
})();