<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class SettingController extends Controller
{
     public function index()
    {
        $userId=Auth::user()->id;
        $setting = Setting::where('user_id',$userId)->first();
        $firstRule = Rule::where('user_id', $userId)->first();
        $rules = Rule::orderBy('id', 'desc')->with('products')->where('user_id',$userId)->paginate(5);
        $productTitles = $this->getProductTitleForListing($rules);
        $collectionTitles = $this->getCollectionTitleForListing($rules);
        if ($rules->count() == 1 && $firstRule['rule_applied_to']!= 'all') {
            return view('listing.general_settings_listing', compact('rules', 'productTitles', 'collectionTitles'));
        }
        elseif ($rules->count() == 1 && $firstRule['rule_applied_to']== 'all') {
            return view('listing.general_settings_listing', compact('rules'));
        }
        if ($rules->count() > 1 ) {
            return view('listing.general_settings_listing', compact('rules', 'productTitles', 'collectionTitles'));
        } elseif($rules->count() < 1) {
            return view('forms.general_settings_form', compact('setting'));
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


    public function generalSettingForm()
    {
        $userId=Auth::user()->id;
        $setting = Setting::where('user_id',$userId)->first();
        return view('forms.general_settings_form', compact('setting'));
    }
    public function store(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'text_color' => 'required|max:191',
                'text_background_color' => 'required|max:191',
                'change_btn_text' => 'required|max:191',
                'update_btn_text' => 'required|max:191',
                'cancel_btn_text' => 'required|max:191',
                'merchant_msg' => 'required_if:enable_merchant_msg,==,1',
            ],
            [
                'change_btn_text.required' => 'This field is required',
                'text_background_color.required' => 'This field is required',
                'change_btn_text.required' => 'This field is required',
                'update_btn_text.required' => 'This field is required',
                'cancel_btn_text.required' => 'This field is required',
                'merchant_msg.required_if' => 'This field is required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        } else {
            $dataArray = [
                'user_id' => Auth::user()->id,
                'enable_app' => $request->enable_app,
                'customize_change_btn' => $request->customize_change_btn,
                'text_color' => $request->text_color,
                'text_background_color' => $request->text_background_color,
                'change_btn_text' => $request->change_btn_text,
                'update_btn_text' => $request->update_btn_text,
                'cancel_btn_text' => $request->cancel_btn_text,
                'enable_merchant_msg' => $request->enable_merchant_msg,
                'merchant_msg' => $request->merchant_msg,
                'enable_customer_msg' => $request->enable_customer_msg,
                'enable_survey_question' => $request->enable_survey_question
            ];
            $dataToUpdate = [
                'enable_app',
                'customize_change_btn',
                'text_color',
                'text_background_color',
                'change_btn_text',
                'update_btn_text',
                'cancel_btn_text',
                'enable_merchant_msg',
                'merchant_msg',
                'enable_customer_msg',
                'enable_survey_question'
            ];
            $data = $request->except('_token');
            $saveSetting = Setting::upsert($dataArray, ['user_id'], $dataToUpdate);
            return response()->json([
                'status' => 200,
                'message' => 'Setting Saved Successfully.'
            ]);
        }
    }
}
