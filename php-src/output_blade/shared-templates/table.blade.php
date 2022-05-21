@if ($table->showPagerOnHead() && $table->getPager())
    {!! $table->getPager()->render() !!}
@endif

{!! $table->getHeaderFilter() ? $table->getHeaderFilter()->renderStart() : ($table->getFooterFilter() ? $table->getFooterFilter()->renderStart() : '') !!}

<table class="{{ $table->getClassesInString() }}">
    <thead>
    <tr>
        @foreach ($table->getColumns() as $column)
            @if ($table->getOrder() && $table->getOrder()->isInOrder($column))
                <th><a href="{{ $table->getOrder()->getHref($column) }}">{{ $table->getOrder()->getHeaderText($column) }}</a></th>
            @else
                <th>{{ $column->getHeaderText() }}</th>
            @endif
        @endforeach
    </tr>
    @if ($table->getHeaderFilter())
        <tr>
            @foreach ($table->getColumns() as $column)
                @if ($column->hasHeaderFilterField())
                    <th>{!! $table->getHeaderFilter()->renderHeaderInput($column) !!}</th>
                @else
                    <th></th>
                @endif
            @endforeach
        </tr>
    @endif
    </thead>
    <tbody>
    @foreach ($table->getTableData() as $row)
        <tr {!! $row->getCellStyle($row->getSource()) !!}>
            @foreach ($row as $col)
                <td {!! $col->getCellStyle($row->getSource()) !!}>{!! $col->translate($row->getSource()) !!}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
    @if ($table->getFooterFilter())
        <tfoot>
        <tr>
            @foreach ($table->getColumns() as $column)
                @if ($column->hasFooterFilterField())
                    <td>{!! $table->getFooterFilter()->renderFooterInput($column) !!}</td>
                @else
                    <td></td>
                @endif
            @endforeach
        </tr>
        </tfoot>
    @endif
</table>

{!! $table->getHeaderFilter() ? $table->getHeaderFilter()->renderEnd() : ($table->getFooterFilter() ? $table->getFooterFilter()->renderEnd() : '') !!}

@if ($table->showPagerOnFoot() && $table->getPager())
    {!! $table->getPager()->render() !!}
@endif

<div class="clearfix"></div>
@if ($table->getFormName() && ($table->getHeaderFilter() || $table->getFooterFilter()))
    <script>
        $('input').keyup(function (e) {
            if (e.which == 13) {
                $(this).parents('[name={{  $table->getFormName() }}]').submit();
            }
        });
        $('select[data-toggle]').change(function (e) {
            e.preventDefault();
            var $ajaxModal = $('#ajaxModal');
            var $form = $(this).parents('[name={{ $table->getFormName() }}]');
            $ajaxModal.find('.modal-dialog').removeClass('modal-lg');
            var selectValue = $(this).val();
            if (($form.find('.multiselect:checked').length > 0) && ('' != selectValue)) {
                if($form.attr('data-wide')) {
                    $ajaxModal.find('.modal-dialog').addClass('modal-lg');
                }
                var baseUrl = 'https://' + (new URL(window.location.href)).host;
                $.ajax({
                    type: $form.attr('method'),
                    url: (new URL($form.attr('action'), baseUrl)).pathname,
                    data: $form.serialize(),
                    success: function ($data) {
                        $ajaxModal.find('.modal-content').html($data);
                        $ajaxModal.modal('show');
                        datepicker();
                    }
                });
            }
            var elements = $(this).children();
            for(var i = 0; i < elements.length; i++){
                elements[i].selected = false;
            }

            e.stopPropagation();
            return false;
        });
        $('select:not([data-toggle])').change(function () {
            $(this).parents('[name={{ $table->getFormName() }}]').submit();
        });
    </script>
@endif
