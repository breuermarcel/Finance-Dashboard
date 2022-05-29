@if($searchResults->count() > 0)
    <div class="modal fade" id="searchResults" tabindex="-1" aria-labelledby="searchResults" aria-hidden="false"
         aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Search Results') }}:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="d-none">
                            <tr>
                                <th class="col text-capitalize">{{ trans('Symbol') }}</th>
                                <th class="col text-capitalize">{{ trans('Name') }}</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="col text-capitalize"></th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($searchResults as $stock)
                                <tr class="cursor"
                                    onclick="location.href = '{{ route('stocks.show', $stock->symbol) }}';">
                                    <td class="col">{{ $stock->symbol }}</td>
                                    <td class="col">{{ $stock->name }}</td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="col text-end">
                                            <form class="d-inline" method="POST" action="{{ route('stocks.destroy', $stock) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">{{ trans('Delete') }}</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ trans('No results') }}.</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


