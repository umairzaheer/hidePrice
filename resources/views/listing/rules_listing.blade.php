<section class="full-width rule-data">
    <article>
        <div class="column twelve card">
            <div class="has-sections">
                <div class="columns twelve">
                    <div class="align-center">
                        <input type="search" placeholder="Rule title, Rule status, Rule applied to" id="search-rule">
                    </div>
                </div>
            </div>
            <div class="row"></div>
            <div class="has-sections">
                <div class="columns twelve">
                    <div class="overflow-container" id="table_data">
                        <table id="login-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Rule Title</th>
                                    <th>Status</th>
                                    <th>Rule Applied To</th>
                                    <th>Product/Collections</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="search-data">
                                @foreach ($rules as $record)
                                    <tr id="{{ $record['id'] }}">
                                        <td><span class="highlight-warning">{{ $record->id }}</span></td>
                                        <td>{{ $record['rule_title'] }}</td>
                                        <td>
                                            @if ($record['enable_rule'] == 0)
                                                <span class="tag yellow">Inactive</span>
                                            @else
                                                <span class="tag green">Active</span>
                                            @endif
                                        </td>
                                        @if ($record['rule_applied_to'] == 'all')
                                            <td>All</td>
                                        @elseif($record['rule_applied_to'] == 'product')
                                            <td>Product</td>
                                        @elseif($record['rule_applied_to'] == 'collection')
                                            <td>Collection</td>
                                        @endif
                                        @if ($record['rule_applied_to'] == 'product')
                                            <td>
                                                @if ($productTitles ?? '')
                                                    @foreach ($record->products as $singleProduct)
                                                        @foreach ($productTitles as $productKey => $productValue)
                                                            @if ($singleProduct->product_id == $productKey)
                                                                <span>{{ $productValue }} </span><br>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                {{-- @else
                                                    <p>No Product</p> --}}
                                                @endif
                                            </td>    
                                                @elseif($record['rule_applied_to'] == 'collection')
                                                <td>
                                                    @if ($collectionTitles ?? '')
                                                        @foreach ($record->products  as $singleProduct)
                                                            @foreach ($collectionTitles as $productKey => $productValue)
                                                                @if ($singleProduct->collection_id == $productKey)
                                                                    <span>{{ $productValue }} </span><br>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    {{-- @else
                                                        <p>No Collection</p> --}}
                                                    @endif
                                                </td>
                                            @elseif($record['rule_applied_to'] == 'all')
                                                <td>
                                                    <p></p>
                                                </td>    
                                        @endif
                                        </td>
                                        <td>
                                            <a href="edit-rule/{{ $record['id'] }}?shop={{ Auth::user()->name}}&host={{app('request')->input('host')}}"><button value="{{ $record['id'] }}"
                                                    class="secondary icon-edit edit-rul" id="edit-rul"
                                                    data-id="{{ $record['id'] }}"></button></a>
                                            <button value="{{ $record['id'] }}" class="secondary icon-trash"
                                                id="delete-rule"></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="pagination" class="columns twelve"
                style="display: flex; justify-content:center; margin-bottom:15px;">
                {!! $rules->links('pagination.pagination') !!}
            </div>
        </div>
    </article>
</section>