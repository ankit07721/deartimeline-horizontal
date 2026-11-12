jQuery(document).ready(function ($) {
    "use strict";

    // --- REPEATER LOGIC ---
    const $repeaterContainer = $('#tsp-repeater-rows');
    const $addRowBtn = $('#tsp-add-row-btn');

    function initDatePicker($elements) {
        if (typeof flatpickr !== 'undefined') {
            $elements.flatpickr({
                dateFormat: "Y-m-d",
                disableMobile: true,
                theme: "light"
            });
        }
    }

    // Initialize the first row
    initDatePicker($('.dearti-datepicker'));

    $addRowBtn.on('click', function () {
        const $firstRow = $repeaterContainer.find('.tsp-repeater-row').first();
        const $newRow = $firstRow.clone();

        // Clear inputs
        $newRow.find('input, textarea').val('');

        // Remove existing flatpickr instance if cloned
        if ($newRow.find('.dearti-datepicker').get(0)._flatpickr) {
            $newRow.find('.dearti-datepicker').get(0)._flatpickr.destroy();
        }

        // Add remove button if it doesn't have one
        if ($newRow.find('.tsp-remove-row-btn').length === 0) {
            $newRow.append('<button type="button" class="tsp-remove-row-btn" style="position:absolute; top:10px; right:10px; background:#fee2e2; color:#ef4444; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:12px; font-weight:bold; display:flex; align-items:center; justify-content:center;">&times;</button>');
        }

        $repeaterContainer.append($newRow);

        // Init datepicker on the new row
        initDatePicker($newRow.find('.dearti-datepicker'));
    });

    $(document).on('click', '.tsp-remove-row-btn', function () {
        $(this).closest('.tsp-repeater-row').remove();
    });

    // --- AJAX EVENT CREATION ---
    $('#tsp-add-event-form').on('submit', function (e) {
        e.preventDefault();
        const $form = $(this);
        const $status = $('#tsp-form-status');
        const formData = new FormData(this);

        formData.append('action', 'dearti_create_event');
        formData.append('security', dearti_ajax.nonce);

        $status.text('Publishing Events...').css('color', '#4f46e5');
        $form.find('button').prop('disabled', true).css('opacity', '0.5');

        $.ajax({
            url: dearti_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    $status.text(response.data).css('color', '#22c55e');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    $status.text(response.data).css('color', '#ef4444');
                    $form.find('button').prop('disabled', false).css('opacity', '1');
                }
            },
            error: function () {
                $status.text('Connection error.').css('color', '#ef4444');
                $form.find('button').prop('disabled', false).css('opacity', '1');
            }
        });
    });

    // Simple close logic for the Add New Event modal
    $(document).on('click', '#tsp-add-modal-overlay, .tsp-close-btn', function (e) {
        if (e.target === this || $(e.target).hasClass('tsp-close-btn')) {
            $(this).closest('.tsp-modal-overlay').fadeOut(300);
        }
    });

    // --- SCROLL REVEAL ANIMATION ---
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                $(entry.target).addClass('is-visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    $('.tsp-reveal').each(function () {
        revealObserver.observe(this);
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $('.tsp-modal-overlay:visible').length) {
            $('.tsp-modal-overlay:visible').fadeOut(300);
        }
    });

    // --- ADMIN IMAGE SELECTOR ---
    let dearti_media_frame;
    $(document).on('click', '#dearti_select_image', function (e) {
        e.preventDefault();

        // If the frame already exists, open it.
        if (dearti_media_frame) {
            dearti_media_frame.open();
            return;
        }

        // Create the media frame.
        dearti_media_frame = wp.media({
            title: 'Select Featured Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        // When an image is selected, run a callback.
        dearti_media_frame.on('select', function () {
            const attachment = dearti_media_frame.state().get('selection').first().toJSON();
            $('#dearti_image_id').val(attachment.id);
            $('#dearti-image-preview').html('<img src="' + attachment.url + '" style="width:100%; height:auto;">');
            $('#dearti_remove_image').show();
        });

        // Finally, open the modal.
        dearti_media_frame.open();
    });

    $(document).on('click', '#dearti_remove_image', function (e) {
        e.preventDefault();
        $('#dearti_image_id').val('');
        $('#dearti-image-preview').html('<span class="dashicons dashicons-format-image" style="font-size: 40px; width: 40px; height: 40px; color: #94a3b8;"></span>');
        $(this).hide();
    });

    // --- ADMIN QUICK ADD / EDIT EVENT (Live Preview) ---
    if ($('#dearti-quick-add-btn').length) {

        // Init datepicker with mm/dd/yyyy display format
        var quickDatePicker = null;
        if (typeof flatpickr !== 'undefined') {
            quickDatePicker = flatpickr('#dearti-quick-date', {
                dateFormat: "Y-m-d",
                altFormat: "m/d/Y",
                altInput: true,
                disableMobile: true
            });
        }

        function resetQuickForm() {
            $('#dearti-quick-add-form')[0].reset();
            $('#dearti-quick-post-id').val('');
            $('#dearti-quick-image-id').val('');
            $('#dearti-quick-img-preview').html('<span class="dashicons dashicons-format-image" style="font-size:40px; width:40px; height:40px; color:#94a3b8;"></span>');
            $('#dearti-quick-remove-image').hide();
            $('#dearti-quick-status').text('').css('color', '');
            $('#dearti-quick-add-form button[type="submit"]').prop('disabled', false).css('opacity', '1').text('Save Event');
            $('#dearti-quick-add-title').text('Add New Timeline Item');
            if (quickDatePicker) {
                quickDatePicker.clear();
            }
        }

        function closeQuickAdd() {
            $('#dearti-quick-add-overlay').fadeOut(200);
            resetQuickForm();
        }

        // Open modal for adding new event
        $(document).on('click', '#dearti-quick-add-btn', function () {
            resetQuickForm();
            $('#dearti-quick-add-overlay').fadeIn(200).css('display', 'flex');
        });

        // Open modal for editing existing event
        $(document).on('click', '.dearti-edit-btn', function () {
            var postId = $(this).data('post-id');
            resetQuickForm();

            $.post(dearti_ajax.ajax_url, {
                action: 'dearti_admin_get_event',
                security: dearti_ajax.nonce,
                post_id: postId
            }, function (response) {
                if (response.success) {
                    var data = response.data;
                    $('#dearti-quick-post-id').val(data.post_id);
                    $('#dearti-quick-add-title').text('Edit Event');
                    $('[name="title"]').val(data.title);
                    if (data.date) {
                        if (quickDatePicker) {
                            quickDatePicker.setDate(data.date, true);
                        } else {
                            $('[name="date"]').val(data.date);
                        }
                    }
                    $('[name="color"]').val(data.color || '#4f46e5');
                    $('[name="description"]').val(data.description);
                    $('[name="img_width"]').val(data.img_width);
                    $('[name="img_height"]').val(data.img_height);
                    if (data.image_id) {
                        $('#dearti-quick-image-id').val(data.image_id);
                        $('#dearti-quick-img-preview').html('<img src="' + data.image_url + '" style="width:100%; height:auto;">');
                        $('#dearti-quick-remove-image').show();
                    }
                    $('#dearti-quick-add-form button[type="submit"]').text('Update Event');
                    $('#dearti-quick-add-overlay').fadeIn(200).css('display', 'flex');
                } else {
                    alert(response.data);
                }
            }, 'json').fail(function () {
                alert('Failed to load event data.');
            });
        });

        $(document).on('click', '#dearti-quick-add-close, #dearti-quick-cancel', closeQuickAdd);

        $(document).on('click', '#dearti-quick-add-overlay', function (e) {
            if (e.target === this) closeQuickAdd();
        });

        // Media uploader for quick-add/edit
        var dearti_quick_media_frame;
        $(document).on('click', '#dearti-quick-select-image', function (e) {
            e.preventDefault();
            if (dearti_quick_media_frame) {
                dearti_quick_media_frame.open();
                return;
            }
            dearti_quick_media_frame = wp.media({
                title: 'Select Featured Image',
                button: { text: 'Use this image' },
                multiple: false
            });
            dearti_quick_media_frame.on('select', function () {
                var attachment = dearti_quick_media_frame.state().get('selection').first().toJSON();
                $('#dearti-quick-image-id').val(attachment.id);
                $('#dearti-quick-img-preview').html('<img src="' + attachment.url + '" style="width:100%; height:auto;">');
                $('#dearti-quick-remove-image').show();
            });
            dearti_quick_media_frame.open();
        });

        $(document).on('click', '#dearti-quick-remove-image', function (e) {
            e.preventDefault();
            $('#dearti-quick-image-id').val('');
            $('#dearti-quick-img-preview').html('<span class="dashicons dashicons-format-image" style="font-size:40px; width:40px; height:40px; color:#94a3b8;"></span>');
            $(this).hide();
        });

        // Form submission (create or update)
        $('#dearti-quick-add-form').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $status = $('#dearti-quick-status');
            var $submitBtn = $form.find('button[type="submit"]');
            var postId = $('#dearti-quick-post-id').val();
            var isEdit = postId && postId.length > 0;

            // Get date in Y-m-d format from flatpickr
            var dateVal = '';
            var dateInput = document.getElementById('dearti-quick-date');
            if (dateInput && dateInput._flatpickr && dateInput._flatpickr.selectedDates.length > 0) {
                dateVal = dateInput._flatpickr.formatDate(dateInput._flatpickr.selectedDates[0], 'Y-m-d');
            }

            var data = {
                security: dearti_ajax.nonce,
                title: $form.find('[name="title"]').val(),
                date: dateVal,
                color: $form.find('[name="color"]').val(),
                description: $form.find('[name="description"]').val(),
                img_width: $form.find('[name="img_width"]').val(),
                img_height: $form.find('[name="img_height"]').val(),
                image_id: $form.find('[name="image_id"]').val()
            };

            if (isEdit) {
                data.action = 'dearti_admin_update_event';
                data.post_id = postId;
                $status.text('Updating event...').css('color', '#4f46e5');
            } else {
                data.action = 'dearti_admin_quick_add_event';
                $status.text('Creating event...').css('color', '#4f46e5');
            }

            $submitBtn.prop('disabled', true).css('opacity', '0.5');

            $.post(dearti_ajax.ajax_url, data, function (response) {
                if (response.success) {
                    var msg = isEdit ? 'Event updated! Refreshing preview...' : 'Event created! Refreshing preview...';
                    $status.text(msg).css('color', '#22c55e');
                    setTimeout(function () {
                        closeQuickAdd();
                        // Refresh preview via AJAX
                        $.post(dearti_ajax.ajax_url, {
                            action: 'dearti_render_preview',
                            security: dearti_ajax.nonce
                        }, function (html) {
                            $('#dearti-preview-content').html(html);
                            // Re-init horizontal navigation
                            var hWrappers = document.querySelectorAll('.deartimeline-horizontal-wrapper');
                            for (var i = 0; i < hWrappers.length; i++) {
                                if (window.deartiInitHorizontal) {
                                    window.deartiInitHorizontal(hWrappers[i]);
                                }
                            }
                            // Re-init reveal animations
                            $('.tsp-reveal').each(function () {
                                revealObserver.observe(this);
                            });
                        });
                    }, 1000);
                } else {
                    $status.text(response.data).css('color', '#ef4444');
                    $submitBtn.prop('disabled', false).css('opacity', '1');
                }
            }, 'json').fail(function () {
                $status.text('Connection error.').css('color', '#ef4444');
                $submitBtn.prop('disabled', false).css('opacity', '1');
            });
        });
    }

    // --- ADMIN DELETE EVENT (Live Preview) ---
    if ($('#dearti-preview-content').length) {

        // Show action buttons on hover
        $(document).on('mouseenter', '.tsp-item, .dearti-h-item', function () {
            $(this).find('.dearti-action-btns').css('display', 'flex');
        });
        $(document).on('mouseleave', '.tsp-item, .dearti-h-item', function () {
            $(this).find('.dearti-action-btns').css('display', 'none');
        });

        // Handle delete click
        $(document).on('click', '.dearti-delete-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $btn = $(this);
            var postId = $btn.data('post-id');

            if (!confirm('Are you sure you want to delete this event? This action cannot be undone.')) {
                return;
            }

            $btn.css('background', '#94a3b8').prop('disabled', true);

            $.post(dearti_ajax.ajax_url, {
                action: 'dearti_admin_delete_event',
                security: dearti_ajax.nonce,
                post_id: postId
            }, function (response) {
                if (response.success) {
                    // Refresh preview
                    $.post(dearti_ajax.ajax_url, {
                        action: 'dearti_render_preview',
                        security: dearti_ajax.nonce
                    }, function (html) {
                        $('#dearti-preview-content').html(html);
                        // Re-init horizontal navigation
                        var hWrappers = document.querySelectorAll('.deartimeline-horizontal-wrapper');
                        for (var i = 0; i < hWrappers.length; i++) {
                            if (window.deartiInitHorizontal) {
                                window.deartiInitHorizontal(hWrappers[i]);
                            }
                        }
                        // Re-init reveal animations
                        $('.tsp-reveal').each(function () {
                            revealObserver.observe(this);
                        });
                    });
                } else {
                    alert(response.data);
                    $btn.css('background', '#ef4444').prop('disabled', false);
                }
            }, 'json').fail(function () {
                alert('Connection error.');
                $btn.css('background', '#ef4444').prop('disabled', false);
            });
        });
    }

    // --- HORIZONTAL TIMELINE NAVIGATION ---
    // Pure Vanilla JS — no heavy slider library needed
    !(function () {
        'use strict';

        function initHorizontal(wrapper) {
            var track = wrapper.querySelector('.deartimeline-track');
            var prev = wrapper.querySelector('.dearti-h-prev');
            var next = wrapper.querySelector('.dearti-h-next');
            var bar = wrapper.querySelector('.dearti-h-progress-bar');
            if (!track || !prev || !next) return;

            function update() {
                var sl = track.scrollLeft;
                var ms = track.scrollWidth - track.clientWidth;
                prev.disabled = sl <= 4;
                next.disabled = sl >= ms - 4;
                if (bar) {
                    var ratio = ms > 0 ? sl / ms : 0;
                    bar.style.width = (8 + ratio * 92) + '%';
                }
            }

            function scrollBy(dir) {
                var item = track.querySelector('.dearti-h-item');
                if (!item) return;
                track.scrollBy({ left: (item.offsetWidth + 32) * dir, behavior: 'smooth' });
            }

            prev.addEventListener('click', function () { scrollBy(-1); });
            next.addEventListener('click', function () { scrollBy(1); });

            track.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    scrollBy(-1);
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    scrollBy(1);
                }
            });

            var isDown = false;
            var startX = 0;
            var startScroll = 0;

            track.addEventListener('mousedown', function (e) {
                if (e.target.closest && e.target.closest('button, a')) return;
                isDown = true;
                startX = e.pageX;
                startScroll = track.scrollLeft;
                track.classList.add('is-dragging');
                e.preventDefault();
            });

            window.addEventListener('mousemove', function (e) {
                if (!isDown) return;
                track.scrollLeft = startScroll - (e.pageX - startX);
            });

            window.addEventListener('mouseup', function () {
                if (!isDown) return;
                isDown = false;
                track.classList.remove('is-dragging');
            });

            var ticking = false;
            track.addEventListener('scroll', function () {
                if (!ticking) {
                    window.requestAnimationFrame(function () {
                        update();
                        ticking = false;
                    });
                    ticking = true;
                }
            });

            // Re-check on resize (debounced)
            var rt;
            window.addEventListener('resize', function () {
                clearTimeout(rt);
                rt = setTimeout(update, 120);
            });

            update();
        }

        // Expose for dynamic re-init after preview refresh
        window.deartiInitHorizontal = initHorizontal;

        var wrappers = document.querySelectorAll('.deartimeline-horizontal-wrapper');
        for (var i = 0; i < wrappers.length; i++) {
            initHorizontal(wrappers[i]);
        }
    })();
});
