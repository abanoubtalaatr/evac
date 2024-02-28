<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#agent_search').on('input', function () {
            var query = $(this).val();
            if (query.length >= 0) {
                $.ajax({
                    url: '/admin/agents/search',
                    method: 'GET',
                    data: { query: query },
                    success: function (data) {
                        var resultsContainer = $('.autocomplete-results');
                        resultsContainer.empty();

                        if (data.length > 0) {
                            resultsContainer.show();
                            data.forEach(function (result) {
                                resultsContainer.append('<li class="list-group-item border-top-0 rounded-0" data-id="' + result.id + '">' + result.name + '</li>')
                                    .css('cursor', 'pointer');
                            });
                        } else {
                        @this.set('agent', "no_result")

                            resultsContainer.append('<li class="list-group-item border-top-0 rounded-0" data-id="nr">No results found</li>');
                            resultsContainer.show();
                        }
                    }
                });
            } else {
                // Set 'agent' to "no_result" when search input is empty

            @this.set('agent', "no_result")
                $('.autocomplete-results').hide();
            }
        });

        $(document).on('click', function (event) {
            if (!$(event.target).closest('.input-group').length) {
                $('.autocomplete-results').hide();
            }
        });

        var ul = document.getElementById('list');
        var liSelected;
        var index = -1;

        $(document).on('keydown', function (event) {
            var len = ul.getElementsByTagName('li').length - 1;
            if (event.which === 40) {
                index++;
                if (liSelected) {
                    removeClass(liSelected, 'selected');
                    next = ul.getElementsByTagName('li')[index];
                    if (typeof next !== undefined && index <= len) {
                        liSelected = next;
                    } else {
                        index = 0;
                        liSelected = ul.getElementsByTagName('li')[0];
                    }
                    addClass(liSelected, 'selected');
                } else {
                    index = 0;
                    liSelected = ul.getElementsByTagName('li')[0];
                    addClass(liSelected, 'selected');
                }
            } else if (event.which === 38) {
                if (liSelected) {
                    removeClass(liSelected, 'selected');
                    index--;
                    next = ul.getElementsByTagName('li')[index];
                    if (typeof next !== undefined && index >= 0) {
                        liSelected = next;
                    } else {
                        index = len;
                        liSelected = ul.getElementsByTagName('li')[len];
                    }
                    addClass(liSelected, 'selected');
                } else {
                    index = 0;
                    liSelected = ul.getElementsByTagName('li')[len];
                    addClass(liSelected, 'selected');
                }
            } else if (event.which === 13) {
                if (liSelected) {
                    var selectedName = liSelected.innerText;
                    $('#agent_search').val(selectedName);
                    var travelAgentId = liSelected.dataset.id;
                @this.set('agent', travelAgentId);
                    $('.autocomplete-results').hide();
                }
            }
        });

        $(document).on('click', '#list li', function () {
            var selectedName = $(this).text();
            $('#agent_search').val(selectedName);
            var travelAgentId = $(this).data('id');
        @this.set('agent', travelAgentId);
            $('.autocomplete-results').hide();
        });

        function removeClass(el, className) {
            if (el.classList) {
                el.classList.remove(className);
            } else {
                el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
            }
        }

        function addClass(el, className) {
            if (el.classList) {
                el.classList.add(className);
            } else {
                el.className += ' ' + className;
            }
        }
    });
</script>
