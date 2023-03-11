<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Ruule;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input; 
use Illuminate\Support\Facades\Validator;


class RuuleController extends Controller
{
    public function index()
    {
        $data = Ruule::orderBy('id', 'desc')->with('products')->paginate(5);
        return view('rule.index', compact('data'));
    }

    public function addRule()
    {
        $ruule = new Ruule();
        return view('rule.insert_rule', compact('ruule'));
    }

    public function store(Request $request)
    { 
        if(!$request->id){
        $validator = Validator::make($request->all(), [
            'rule_title' => 'required|max:191',
            'categories' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            if($request->products){
            $rule = new Ruule;
            $rule->user_id = Auth::user()->id;
            $rule->rule_status = $request->enable_rule;
            $rule->rule_title = $request->rule_title;
            $rule->rule_category = $request->categories;
            $rule->save();
            $ruleId= $rule->id;
            $shop = Auth::user();

            for ($i = 0; $i < count($request->products); $i++) {
                $result = $shop->api()->rest('GET', '/admin/api/2022-04/products/' .
                    $request->products[$i] . '.json');
                Product::create([
                    'ruules_id' => $ruleId,
                    'product_id' => $request->products[$i],
                    'product_title' => $result['body']['product']['title']
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Rule Added Successfully.',
                'rule' => $request->enable_rule,
                'ruleId' => $ruleId
            ]);
        }
        else{


            $rule = new Ruule;
            $rule->user_id = Auth::user()->id;
            $rule->rule_status = $request->enable_rule;
            $rule->rule_title = $request->rule_title;
            $rule->rule_category = $request->categories;
            $rule->save();
            // return $request;
            $ruleId= $rule->id;
            return response()->json([
                'status' => 200,
                'message' => 'Rule Added Successfully.',
                'ruleId' => $ruleId,
                'rule' => $request->enable_rule,
            ]);
        }
        }
    }
    else {
        $validator = Validator::make($request->all(), [
            'rule_title' => 'required|max:191',
            'categories' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $id = $request->id;
            $rule = Ruule::find($id);
            if ($rule) {
                $rule->rule_status = $request->status;
                $rule->rule_title = $request->rule_title;
                $rule->rule_category = $request->categories;
                $rule->update();

                $ruleId = $rule->id;
                Product::where('ruules_id', $ruleId)->delete();
                $shop = Auth::user();
                
                if($request['categories'] == 'Products'){
                    foreach ($request->product as $pro) {
                        $products[] = $pro;
                    }
    
                    for ($i = 0; $i < count($request->product); $i++) {
                        $result = $shop->api()->rest('GET', '/admin/api/2022-04/products/' .
                            $request->product[$i] . '.json');
    
                        Product::create([
                            'ruules_id' => $ruleId,
                            'product_id' => $request->product[$i],
                            'product_title' => $result['body']['product']['title']
                        ]);
                    }
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Rule Updated Successfully.'
                ]);
            }
        else{
            $rule = Ruule::find($id);
            if ($rule) {
                $rule->rule_status = $request->status;
                $rule->rule_title = $request->rule_title;
                $rule->rule_category = $request->categories;
                $rule->update();
            }
            return response()->json([
                'status' => 200,
                'message' => 'Rule Updated Successfully.'
            ]);
        }
        }
    }
    }


    public function getallproducts(Request $request)
    {
        $searchTitle = $request->get('term');
        if ($searchTitle == '') {
        }
        $fields = [
            'fields' => 'id,title',
            'limit' => 249
        ];
        $shop = Auth::user();
        $result = $shop->api()->rest('GET', '/admin/products.json', $fields);

        foreach ($result['body']['products'] as $product) {
            $dataArray[] = (object)[
                'text' => $product['title'],
                'id' => $product['id']
            ];
        }
        return response()->json($dataArray);
    }

    public function storeProduct(Request $request)
    {
        for ($i = 0; $i < count($request->product); $i++) {
            Ruule::create([
                'product_id' => $request->product[$i],
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Product Added Successfully.'
        ]);
    }

    public function edit(Request $request, $id)
    {
        $ruule = Ruule::where('id', $id)->with('products')->first();
        if($ruule['rule_category']!= 'All'){
            // push all products to array
            foreach ($ruule['products'] as $product) {
                $dataArray[$product->product_id] = $product->product_title;
            }
        }
        if(isset($dataArray)){
            return view('rule.edit', compact('ruule', 'dataArray'))->render();
        }else {
            return view('rule.edit', compact('ruule'))->render();
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rule_title' => 'required|max:191',
            'categories' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $id = $request->id;
            $rule = Ruule::find($id);
            if ($rule) {
                $rule->rule_status = $request->status;
                $rule->rule_title = $request->rule_title;
                $rule->rule_category = $request->categories;
                $rule->update();

                $ruleId = $rule->id;
                Product::where('ruules_id', $ruleId)->delete();
                $shop = Auth::user();
                
                if($request['categories'] == 'Products'){
                    foreach ($request->product as $pro) {
                        $products[] = $pro;
                    }
    
                    for ($i = 0; $i < count($request->product); $i++) {
                        $result = $shop->api()->rest('GET', '/admin/api/2022-04/products/' .
                            $request->product[$i] . '.json');
    
                        Product::create([
                            'ruules_id' => $ruleId,
                            'product_id' => $request->product[$i],
                            'product_title' => $result['body']['product']['title']
                        ]);
                    }
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Rule Updated Successfully.'
                ]);
            }
        else{
            $rule = Ruule::find($id);
            if ($rule) {
                $rule->rule_status = $request->status;
                $rule->rule_title = $request->rule_title;
                $rule->rule_category = $request->categories;
                $rule->update();
            }
            return response()->json([
                'status' => 200,
                'message' => 'Rule Updated Successfully.'
            ]);
        }
        }
    }

    public function destroy($id)
    {
        $ruule = Ruule::find($id);
        if ($ruule) {
            $ruule->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Rule Deleted Successfully.',
                'id' => $ruule->id
            ]);
        }
    }

    public function search(Request $request)
    { 
        $data = Ruule::where('rule_title', 'LIKE', '%' . $request->search . "%")
        ->orWhere('rule_category', 'LIKE', '%' . $request->search . "%")
        ->orderBy('id', 'asc')->with('products')->paginate(5);
        $data = view('rule.search', compact('data'))->render();
        // return view('rule.index', compact('data'));
        return response()->json($data);
            // $search['data'] = Ruule::where('rule_title', 'LIKE', '%' . $request->search . "%")
            // ->orWhere('rule_category', 'LIKE', '%' . $request->search . "%")
            //     ->orderBy('id', 'asc')->paginate(5);
            //     // return ($request->search);
            // if ($search['data']) {
            //     $search['paginate'] = view('rule.index', $search)->render();
            //     // $search['data'] = view('rule.search', $search)->render();
            //     return response()->json($search['data']);
            // }
    }

    public function pagination(Request $request)
    { 
            // $search['data'] = Ruule::where('rule_title', 'LIKE', '%' . $request->search . "%")
            // ->orWhere('rule_category', 'LIKE', '%' . $request->search . "%")
            //     ->orderBy('id', 'asc')->paginate(5);
            $data = Ruule::where('rule_title', 'LIKE', '%' . $request->search . "%")
                ->orWhere('rule_category', 'LIKE', '%' . $request->search . "%")
                ->orderBy('id', 'asc')->with('products')->paginate(5);
                $data = view('rule.index', compact('data'))->render();
                // return view('rule.index', compact('data'));
                return response()->json($data);
            // if ($search['data']) {
            //     $search['paginate'] = view('rule.search', $search)->render();
            //     // $search['data'] = view('rule.search', $search)->render();
            //     return response()->json($search);
            // } else {
            //     return response()->json([
            //         'status' => 404,
            //         'message' => 'No Rule Found.'
            //     ]);
            // }
        
    }

    public function matchProduct()
    {
        return "Hello";
        $data = Ruule::orderBy('id', 'asc')->with('products')->get();
        return response()->json($data);
    }
}
