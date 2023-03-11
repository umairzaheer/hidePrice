
    <table>
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
                <tr>
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
                            <span style="color: blue";>{{ $products['product_title'] }}</span>
                            <?php $num_count = $num_count + 1;
                            if ($num_count < $num_of_items) {
                                echo ',';
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
 
    
