/********************************************
 table generator
 ********************************************/
doravel.ready(() => {
    // Handle the "Select All" checkbox change
    const selectAllCheckbox = $(".x-table-generator .select-all");
    const checkbox = $(".x-table-generator .form-check-input:not(.select-all)");

    selectAllCheckbox.change(function () {
        const isChecked = $(this).prop("checked");
        $(".x-table-generator .form-check-input").prop("checked", isChecked);
        updateSelectAllStatus();
    });

    // Handle individual row checkbox changes
    checkbox.change(function () {
        updateSelectAllStatus();
    });

    // Update the status of the "Select All" checkbox
    function updateSelectAllStatus() {
        const totalCheckboxes = checkbox.length;

        const checkedCheckboxes = $(".x-table-generator .form-check-input:not(.select-all):checked").length;

        /*checkedCheckboxes !==0
        this logic for when table is empty and control checkbox in thead table */
        if (checkedCheckboxes === totalCheckboxes && checkedCheckboxes !== 0) {
            selectAllCheckbox.prop("checked", true).prop("indeterminate", false);
        } else if (checkedCheckboxes > 0) {
            selectAllCheckbox.prop("checked", false).prop("indeterminate", true);
        } else {
            selectAllCheckbox.prop("checked", false).prop("indeterminate", false);
        }
    }

    // Initial status update
    updateSelectAllStatus();
}, ".x-table-generator");

$(".x-filter-item").each(function () {
    $(this)
        .find(".x-filter-item.text-truncate")
        .each(function () {
            const $span = $(this);

            // Add a small tolerance (1-2px) to account for browser rounding differences
            if ($span[0].scrollWidth > $span[0].clientWidth + 1) {
                const trimmedText = $span.text().trim();
                const $icon = $(`<i class="fa-regular fa-circle-info ms-2 text-gray-600 cursor-pointer"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                data-bs-title="${trimmedText}"></i>`);
                $span.parent().append($icon);
            }
        });
});

/***********************************************************
 table paginate
 ************************************************************/
doravel.ready(() => {
    $(".x-table-page-count-container .x-dropdown-item-component").on("click", function () {
        const selectedSize = $(this).text().trim();
        $("#per-page").val(selectedSize);
        document.forms["set-page-form"].submit();
    });

    function clearInputAndSubmitForm(inputElementName) {
        // find all elements with name starting with inputElementName -> date range picker
        const matchingElements = $(`[name='${inputElementName}'],[name='${inputElementName}[]'] `);

        if (matchingElements.length) {
            matchingElements.each(function () {
                $(this).val("").trigger("change");
            });
            const form = matchingElements.closest("form");
            if (form.length) {
                form.submit();
            }
        }
    }

    function showLoadingIndicator(badge) {
        badge.removeClass("fa-xmark").addClass("fa-spinner fa-spin");
        badge.closest(".badge").css({ opacity: 0.5, "pointer-events": "none" });
    }

    $(".x-remove-badge").click(function () {
        const inputElementName = $(this).data("related-element");
        showLoadingIndicator($(this));
        if (inputElementName) {
            clearInputAndSubmitForm(inputElementName);
        }
    });

    $(".x-filter-clear").click(function () {
        $(this).addClass("disabled");
        $(this)
            .closest(".x-filter-info")
            .find(".x-remove-badge")
            .each((index, item) => {
                showLoadingIndicator($(item));
            });
        const resetButton = $(this).closest(".x-filter-info").prev().find(".x-filter-reset-button");
        resetButton[0].click();
    });
}, ".x-table-generator");

$(".x-table-card .accordion-collapse").on("show.bs.collapse", function () {
    $(".x-table-card .x-filter-info").hide();
});

$(".x-table-card .accordion-collapse").on("hide.bs.collapse", function () {
    $(".x-table-card .x-filter-info").show();
});
