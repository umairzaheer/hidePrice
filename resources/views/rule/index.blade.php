@extends('shopify-app::layouts.default')

<div id = "pagination-data">
<section>
</section>

<section class="full-width">
    <article class="row">
        <a href="add-rule" id="add_rule" >
            <input class="add_rule"  type="submit" value="Add Rule"></a>
    </article>
</section>

<div id="success_message" style="display:none"></div>
<section class="full-width">
    <article>
        <div class="card">
            <div class="search">
                <input type="search" placeholder="Search" id="search">
            </div>

            <body id="course">
                <table id="new_record" class="results">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $record)
                            <tr id="{{ $record['id'] }}">
                                <input type = "hidden" id = "{{ $record['id'] }}">
                                <td><span class="highlight-warning">{{ $record['id'] }}</span></td>
                                <td>
                                    @if ($record['rule_status'] == 0)
                                        <span class="tag yellow">Inactive</span>
                                    @else
                                        <span class="tag green">Active</span>
                                    @endif
                                </td>
                                <td>{{ $record['rule_title'] }}</td>
                                <td>{{ $record['rule_category'] }}</td>
                                <td>
                                    <?php $product = $record['products'];
                                    $num_count = 0;
                                    $num_of_items = count($product);
                                    ?>
                                    @foreach ($record['products'] as $products)
                                        <span style="color: blue";>{{$products['product_title']}}</span>
                                        <?php $num_count = $num_count + 1;
                                        if ($num_count < $num_of_items) {
                                            echo',';
                                        } ?>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="edit-rule/{{$record['id']}}"><button value="{{$record['id']}}" class="secondary icon-edit edit"></button></a>
                                    <a href="#DeleteModal"><button value="{{$record['id']}}" class="secondary icon-trash deletebtn"></button></a>
                                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id = "pagination">
                    {!! $data->links('pagination.pagination') !!}
                </div>
        </div>
    </article>
</section>

<!-- Delete Model -->
<div id="DeleteModal" class="modalDialog">
    <div>    
        <a href="#close" title="Close" class="close" >X</a>
        <h5>Delete Course</h5>
        <h4>Confirm Delete ?</h4>
        <input type="hidden" id="deleteing_id">
        <button class="delete_rule" >Delete</button>
        <a href="#cancel" title="Close" class="cancel"><button class="secondary">Cancel</button></a>
    </div>
</div>
</div>
