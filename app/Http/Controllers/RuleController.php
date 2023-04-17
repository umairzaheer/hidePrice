<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Rule;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RuleController extends Controller
{
  public function index()
  {
    // $rule = Rule::where('id', $id)->with('products')->first();
    // return $rule;
    return view('models.add_rule_model');
  }

  public function loadRuleData(){
    $userId = Auth::user()->id;
    $rules = Rule::orderBy('id', 'desc')->with('products')->where('user_id',$userId)->paginate(5);
    if ($rules->count() > 0) {
      $productTitles = $this->getProductTitleForListing($rules);
      $collectionTitles = $this->getCollectionTitleForListing($rules);
  }
    return view('listing.rules_listing', compact('rules', 'productTitles', 'collectionTitles'));
  }


  public function getAllProducts(Request $request)
  {
    // return $request->all();
    if ($request->get('type') == 'product') {
      $searchTitle = $request->get('term');
      $shop = Auth::user();
      $dataArray = [];
      if (isset($searchTitle)) {
        $products = $shop->api()->graph('
              {
                products(first:250, query:"title:*' . $searchTitle . '*") {
                  edges {
                    node {
                      id
                      title
                    }
                  }
                }
              }
            ')['body']['data']['products']['edges'];
      } else {
        $products = $shop->api()->graph('
              {
                products(first:250) {
                  edges {
                    node {
                      id
                      title
                    }
                  }
                }
              }
            ')['body']['data']['products']['edges'];
      }
      foreach ($products as $product) {
        array_push($dataArray, array("id" => basename(parse_url($product['node']['id'], PHP_URL_PATH)), "text" => $product['node']['title']));
      }
      return response()->json($dataArray);
    }
    if ($request->get('type') == 'collection') {
      $searchTitle = $request->get('term');
      $shop = Auth::user();
      $dataArray = [];
      if (isset($searchTitle)) {
        $collections = $shop->api()->graph('
                {
                    collections(first: 250, query:"title:*' . $searchTitle . '*") {
                        edges {
                            node {
                                id
                                title
                            }
                        }
                    }
                }
            ')['body']['data']['collections']['edges'];
      } else {
        $collections = $shop->api()->graph('
                    {
                        collections(first: 250) {
                            edges {
                                node {
                                    id
                                    title
                                }
                            }
                        }
                    }
                ')['body']['data']['collections']['edges'];
      }
      foreach ($collections as $collection) {
        array_push($dataArray, array("id" => basename(parse_url($collection['node']['id'], PHP_URL_PATH)), "text" => $collection['node']['title']));
      }
      return response()->json($dataArray);
    }
  }

  //This method was made to store data using UPSERT method.
  // public function store(Request $request)
  // {
  //   // return $request->all();
  //   $validator = Validator::make(
  //     $request->all(),
  //     [
  //       'rule_applied_to' => 'required',
  //       'product_collection_value' => 'required_if:rule_applied_to,==,product,collection',
  //       'rule_title' => 'required'
  //     ],
  //     [
  //       'product_collection_value.required_if' => $request->rule_applied_to . ' field is required',
  //       'customer_category.required' => 'This field is required',
  //       'rule_title.required' => 'Rule Title is required'
  //     ]
  //   );
  //   if ($validator->fails()) {
  //     return response()->json([
  //       'status' => 400,
  //       'errors' => $validator->messages()
  //     ]);
  //   } else {
  //     //check rule Id to update or insert rule
  //     // if($request->rule_id==''){}

  //     $dataArray = [
  //       'id' => $request->rule_id,
  //       'rule_title' => $request->rule_title,
  //       'enable_rule' => $request->enable_rule,
  //       'user_id' => Auth::user()->id,
  //       'rule_applied_to' => $request->rule_applied_to
  //   ];
  //   $dataToUpdate = [
  //     'rule_title',
  //     'enable_rule',
  //     'rule_applied_to'
  // ];
  // $saveRule = Rule::upsert($dataArray, ['id'], $dataToUpdate);
  // $ruleId = $saveRule['id'];

  //     // $data = $request->except('_token');
  //     // $data['user_id'] = Auth::user()->id;
  //     // $dataa = Rule::create($data);
  //     // $ruleId = $dataa->id;
  //     if ($request->rule_applied_to != 'all') {
  //       if (count($request->product_collection_value) > 0) {
  //         for ($i = 0; $i < count($request->product_collection_value); $i++) {
  //           $product = new Product();
  //           if ($request->rule_applied_to == 'product') {
  //             $product->rule_id = $ruleId;
  //             $product->product_id = $request->product_collection_value[$i];
  //             $product->save();
  //           } else if ($request->rule_applied_to == 'collection') {
  //             $product->rule_id = $ruleId;
  //             $product->collection_id = $request->product_collection_value[$i];
  //             $product->save();
  //           }
  //         }
  //       }
  //     }
  //     return response()->json([
  //       'status'  => 200,
  //       'message' => 'Rule Added Successfully.',
  //       'data'    => $saveRule,
  //       'ruleId'  => $ruleId,
  //       // 'ruleFor' => $dataa->rule_for,
  //       // 'count'   => $count
  //     ]);
  //   }
  // }
  public function store(Request $request)
  {
    // return $request->all();
    $validator = Validator::make(
      $request->all(),
      [
        'rule_applied_to' => 'required',
        'product_collection_value' => 'required_if:rule_applied_to,==,product,collection',
        'rule_title' => 'required'
      ],
      [
        'product_collection_value.required_if' => $request->rule_applied_to . ' field is required',
        'customer_category.required' => 'This field is required',
        'rule_title.required' => 'Rule Title is required'
      ]
    );
    if ($validator->fails()) {
      return response()->json([
        'status' => 400,
        'errors' => $validator->messages()
      ]);
    } else {
      //check rule Id to update or insert rule
      if ($request->rule_id != '') {
        // return ('123');
        $ruleId = $request->rule_id;
        return $this->update($request, $ruleId);
      } else {
        $data = $request->except('_token');
        $data['user_id'] = Auth::user()->id;
        $dataa = Rule::create($data);
        $ruleId = $dataa->id;
        if ($request->rule_applied_to != 'all') {
          if (count($request->product_collection_value) > 0) {
            for ($i = 0; $i < count($request->product_collection_value); $i++) {
              $product = new Product();
              if ($request->rule_applied_to == 'product') {
                $product->rule_id = $ruleId;
                $product->product_id = $request->product_collection_value[$i];
                $product->save();
              } else if ($request->rule_applied_to == 'collection') {
                $product->rule_id = $ruleId;
                $product->collection_id = $request->product_collection_value[$i];
                $product->save();
              }
            }
          }
        }
        return response()->json([
          'status'  => 200,
          'message' => 'Rule Added Successfully.',
          'data'    => $dataa,
          'ruleId'  => $ruleId,
          // 'ruleFor' => $dataa->rule_for,
          // 'count'   => $count
        ]);
      }
    }
  }

  public function edit(Request $request, $id)
  {
    // return $request->all();
    $rule = Rule::where('id', $id)->with(['products'])->first();
    // return $rule;
    $shop = Auth::user();
    $ruleId = $rule->id;
    $title = [];
    if ($rule->rule_applied_to == 'product') {
      // $productTitle = [];
      foreach ($rule['products'] as $product) {
        $requestProductApi = $shop->api()->graph('
        {
          product(id: "gid://shopify/Product/' . $product->product_id . '") {
              id
              title
          }
        }
      ');
        // return $requestProductApi;

        $title[basename(parse_url($requestProductApi['body']->data->product->id, PHP_URL_PATH))] = $requestProductApi['body']->data->product->title;
      }
      return view('models.edit_rule', compact('rule', 'ruleId', 'title'));
    } elseif ($rule->rule_applied_to == 'collection') {
      // $title = [];
      foreach ($rule['products'] as $collection) {
        $requestCollectionApi = $shop->api()->graph('
             {
                collection(id: "gid://shopify/Collection/' . $collection->collection_id . '") {
                    id
                    title
                }
             }
            ');
        $title[basename(parse_url($requestCollectionApi['body']->data->collection->id, PHP_URL_PATH))] = $requestCollectionApi['body']->data->collection->title;
      }
      return view('models.edit_rule', compact('rule', 'ruleId', 'title'));
    } else {
      return view('models.edit_rule', compact('rule', 'title'));
    }
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make(
      $request->all(),
      [
        'rule_applied_to' => 'required',
        'product_collection_value' => 'required_if:rule_applied_to,==,product,collection',
        'rule_title' => 'required'
      ],
      [
        'product_collection_value.required_if' => $request->rule_applied_to . ' field is required',
        'customer_category.required' => 'This field is required',
        'rule_title.required' => 'Rule Title is required'
      ]
    );
    if ($validator->fails()) {
      return response()->json([
        'status' => 400,
        'errors' => $validator->messages()
      ]);
    } else {
      //check rule Id to update or insert rule
      $rule = Rule::find($id);
      $data = $request->except('_token');
      $data['user_id'] = Auth::user()->id;
      $rule->fill($data)->save();
      $ruleId = $rule->id;
      Product::where('rule_id', $ruleId)->delete();
      if ($request->rule_applied_to != 'all') {
        if (count($request->product_collection_value) > 0) {
          for ($i = 0; $i < count($request->product_collection_value); $i++) {
            $product = new Product();
            if ($request->rule_applied_to == 'product') {
              $product->rule_id = $ruleId;
              $product->product_id = $request->product_collection_value[$i];
              $product->save();
            } else if ($request->rule_applied_to == 'collection') {
              $product->rule_id = $ruleId;
              $product->collection_id = $request->product_collection_value[$i];
              $product->save();
            }
          }
        }
      }
      return response()->json([
        'status'  => 200,
        'message' => 'Rule Updated Successfully.',
        'data'    => $rule,
        'ruleId'  => $ruleId,
        // 'ruleFor' => $rule->rule_for,
        // 'count'   => $count
      ]);
    }
  }

  
  public function destroy($id)
  {
    $rule = Rule::find($id);
    if ($rule) {
      $userId=Auth::user()->id;
      $rule->delete();
      $rulesCount = Rule::where('user_id',$userId)->count();
      return response()->json([
        'status' => 200,
        'message' => 'Rule Deleted Successfully.',
        'id' => $rule->id,
        'rulesCount' => $rulesCount,

      ]);
    }
  }

  function pagination(Request $request)
  {
    // return $request->all();
    $userId = Auth::user()->id;
    $search = $request->search;
    $paginationSearch = $request->paginationSearch;
    if ($search != "") {
      $data = Rule::orderBy('id', 'desc')->where('rule_title', 'LIKE', "%{$search}%")->orWhere('enable_rule', 'LIKE', "%{$search}%")->orWhere('rule_applied_to', 'LIKE', "%{$search}%")->where('user_id', $userId)->with('products')->paginate(5);
      $productTitles = $this->getProductTitleForListing($data);
      $collectionTitles = $this->getCollectionTitleForListing($data);
      $pagination = $data->render('pagination.pagination');
      return response()->json([
        'status' => 200,
        'data' => $data,
        'pagination' => "$pagination",
        'productTitles' => $productTitles,
        'collectionTitles' => $collectionTitles
      ]);
    } else {
      $data = Rule::orderBy('id', 'desc')->where('rule_title', 'LIKE', "%{$paginationSearch}%")->orWhere('enable_rule', 'LIKE', "%{$paginationSearch}%")->orWhere('rule_applied_to', 'LIKE', "%{$paginationSearch}%")->where('user_id', $userId)->with('products')->paginate(5);
      $productTitles = $this->getProductTitleForListing($data);
      $collectionTitles = $this->getCollectionTitleForListing($data);
      $pagination = $data->render('pagination.pagination');
      return response()->json([
        'status' => 200,
        'data' => $data,
        'productTitles' => $productTitles,
        'collectionTitles' => $collectionTitles,
        'pagination' => "$pagination",
      ]);
    }
  }

  public function getProductTitleForListing($rules)
  {

    $shop = Auth::user();
    $dataArray = [];
    $idsString = '';
    foreach ($rules as $rule) {
      if ($rule->rule_applied_to == 'product') {
        foreach ($rule->products as $product) {
          $idsString .= $product->product_id . ",";
        }
      }
    }
    $idsString = rtrim($idsString, ',');
    $request = $shop->api()->rest(
      "GET",
      "/admin/products.json",
      [
        'ids' => $idsString,
        'fields' => 'id, title',
      ]
    );
    $dataObject = $request['body']->products;
    foreach ($dataObject as $singleItem) {
      $dataArray[$singleItem->id] = $singleItem->title;
    }
    return $dataArray;
  }
  public function getCollectionTitleForListing($rules)
  {
    $shop = Auth::user();
    $idsArray = [];
    $idsString = '';

    foreach ($rules as $rule) {

      if ($rule->rule_applied_to == 'collection') {
        foreach ($rule->products as $collection) {
          $idsArray[] = $collection->collection_id;
        }
      }
    }
    $idsString = implode(",", $idsArray);
    $shopCollections = [];
    if (!empty($idsString)) {
      $ids = explode(',', $idsString);
      $prefixed_ids = array_map(function ($str) {
        return "gid://shopify/Collection/$str";
      }, $ids);
      $prefixed_ids_string = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($prefixed_ids), ENT_NOQUOTES));
      $query = '{
                    nodes(ids:[' . $prefixed_ids_string . ']) {
                      id
                      ...on Collection {
                        title
                  }
              }
          }';
      $productArray = array();
      $result = $shop->api()->graph($query);
      foreach ($result['body']->data->nodes as $key => $nodes) {
        if (!empty($nodes)) {
          $node_id = basename(parse_url($nodes->id, PHP_URL_PATH));
          $shopCollections[$node_id] = $nodes->title;
        }
      }
    }
    return $shopCollections;
  }
}
