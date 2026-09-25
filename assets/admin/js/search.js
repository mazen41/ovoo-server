
"use strict";
(function ($) {
    //header search 
    (function () {

        const $headerSearchWrapper = $(".header-search");
        const $menuElements = $headerSearchWrapper.find(".search-card__item");
        const $searchList = $headerSearchWrapper.find(".search-card__list");
        var selectedIndex = -1;

        $('.open-search').on('click input', function (e) {
            //hide the all modal
            $('.modal').modal("hide");
            const $this = $(this);

            if ($this.val().length) {
                $headerSearchWrapper.find(`[type=search]`).val($this.val())
                $this.val('');
            }
            //open search box
            $('.search-card').css('display', 'block')
            $('.sidebar-overlay').addClass('show');
            $headerSearchWrapper.find(`[type=search]`)[0].focus();
            $menuElements.removeClass('active');
            selectedIndex = -1;
            $searchList.scrollTop(0);
        });

        //search on input
        $headerSearchWrapper.on('input', "[type=search]", function (e) {
            const searchText = $(this).val().toLowerCase().trim();
            const $emptyMessageWrapper = $headerSearchWrapper.find(".search-empty-message");
            var isResult = false;
            selectedIndex = -1;

            $.each($menuElements, function (i, menuElement) {
                const $menuElement = $(menuElement);
                const menuName = $menuElement.find('.title').text();
                const menuSubName = $menuElement.find('.subtitle').text();
                const keyword = $menuElement.data('keyword').toString().toLowerCase();
                const searchInside = `${menuName.toLowerCase()} ${keyword}`;
                if (searchInside.includes(searchText)) {

                    $menuElement.removeClass('d-none');
                    isResult = true;


                    //highlight matching text 
                    const regex = new RegExp(`(${searchText})`, 'gi');
                    if (menuName.toLowerCase().includes(searchText)) {
                        const highlightedText = menuName.replace(regex, '<span class="highlight-text">$1</span>');
                        $menuElement.find('.title').html(highlightedText);
                        $menuElement.find('.subtitle').html(menuSubName);
                        var matchingTitle = true;
                    } else {
                        $menuElement.find('.title').html(menuName);
                        var matchingTitle = false;
                    }

                    if (!matchingTitle) {
                        if (menuSubName.toLowerCase().includes(searchText)) {
                            const highlightedSubtitle = menuSubName.replace(regex, '<span class="highlight-text">$1</span>');
                            $menuElement.find('.subtitle').html(highlightedSubtitle);
                        } else {
                            $menuElement.find('.subtitle').html(menuSubName);
                        }
                    }
                } else {
                    $menuElement.addClass('d-none');
                }
            });

            if (!searchText.length) {
                $emptyMessageWrapper.addClass('d-none');
                $menuElements.removeClass('d-none');
                return;
            }

            if (isResult) {
                $emptyMessageWrapper.addClass('d-none');
            } else {
                $emptyMessageWrapper.removeClass('d-none');
                $emptyMessageWrapper.find(".search-keyword").text(searchText);
            }
        });

        //for array up down and close 
        $(document).on('keydown', function (e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                $('.open-search').click();

            }
            if ($headerSearchWrapper.length && $.contains($headerSearchWrapper[0], e.target)) {
                const $visibleMenuElements = $menuElements.not('.d-none');
                const length = $visibleMenuElements.length;
                if (e.key === 'ArrowDown') {
                    if (selectedIndex + 1 == length) {
                        selectHeaderSearchItem(0); // go to first element if all used scroll
                    } else {
                        if (selectedIndex < $visibleMenuElements.length - 1) {
                            selectHeaderSearchItem(selectedIndex + 1);
                        }
                    }
                } else if (e.key === 'ArrowUp') {
                    if (selectedIndex == 0) {
                        selectHeaderSearchItem(length - 1); // last element is select when first element is selected and pres up key
                    } else if (selectedIndex < 0) {
                        selectHeaderSearchItem(0); // first element is select when first time up press
                    } else {
                        selectHeaderSearchItem(selectedIndex - 1);
                    }
                } else if (e.key === 'Enter' && selectedIndex !== -1) {
                    $visibleMenuElements.eq(selectedIndex).find('a')[0].click();
                } else if (e.key === 'Tab') {
                    e.preventDefault();
                } else if (e.key === 'Escape') {
                    $('.search-card').css('display', 'none')
                    $('.sidebar-overlay').removeClass('show');
                }
            }
        });

        function selectHeaderSearchItem(index) {
            const $visibleMenuElements = $menuElements.not('.d-none');
            if (selectedIndex !== -1) {
                $visibleMenuElements.eq(selectedIndex).removeClass('active');
            }
            selectedIndex = index;
            const $selectedItem = $visibleMenuElements.eq(selectedIndex);
            $selectedItem.addClass('active');
            $visibleMenuElements.eq(selectedIndex)[0].scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });
        }
    })();

})(jQuery);