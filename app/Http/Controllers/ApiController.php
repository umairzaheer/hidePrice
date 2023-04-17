<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rule;
use App\Models\Product;
use App\Models\Setting;


class ApiController extends Controller
{
    public function getProductCollectionData(Request $request)
    {
        $domain = $request->shop;
        $shop = User::where('name', $domain)->first();
        $userId = $shop->id;
        $requestMoney = $shop->api()->rest('GET', '/admin/shop.json', ['fields' => 'currency,money_format,money_with_currency_format,money_in_emails_format,money_with_currency_in_emails_format']);
        if (isset($requestMoney['body']) && !empty($requestMoney['body'])) {
            $result = $requestMoney['body']->shop;
            $selectedMoneyFormat = $result->money_format;
            if (strpos($selectedMoneyFormat, '{{ amount }}') !== false) {
                $moneyFormat = str_replace('{{ amount }}', '', $selectedMoneyFormat);
            } elseif (strpos($selectedMoneyFormat, '{{ amount_no_decimals }}') !== false) {
                $moneyFormat = str_replace('{{ amount_no_decimals }}', '', $selectedMoneyFormat);
            } elseif (strpos($selectedMoneyFormat, '{{ amount_with_comma_separator }}') !== false) {
                $moneyFormat = str_replace('{{ amount_with_comma_separator }}', '', $selectedMoneyFormat);
            } elseif (strpos($selectedMoneyFormat, '{{ amount_no_decimals_with_comma_separator }}') !== false) {
                $moneyFormat = str_replace('{{ amount_no_decimals_with_comma_separator }}', '', $selectedMoneyFormat);
            } elseif (strpos($selectedMoneyFormat, '{{ amount_with_apostrophe_separator }}') !== false) {
                $moneyFormat = str_replace('{{ amount_with_apostrophe_separator }}', '', $selectedMoneyFormat);
            } else {
                $moneyFormat = $selectedMoneyFormat;
            }
        }
        $productIds=[];
        $collectionIds=[];
        $setting = Setting::where('user_id',$userId)->first();
        $rule = Rule::with('products')->where('user_id',$userId)->where('enable_rule', '1')->get();
        foreach($rule as $data)
        {
            if($data['rule_applied_to']=='product')
            {
                foreach($data['products'] as $productId)
                {
                $productIds[] = $productId['product_id'];
                }
            }
            elseif($data['rule_applied_to']=='collection')
            {
                foreach($data['products'] as $collectionId)
                {
                $collectionIds[] = $collectionId['collection_id'];
                }
            }
            else {
                return response()->json([
                    'allProductCollection' => true,
                    'setting'=>$setting,
                    'moneyFormat'=>$moneyFormat

                ]);
            }
        }
        return response()->json([
            'rule' =>$rule,
            'productIds' => $productIds,
            'collectionIds' => $collectionIds,
            'allProductCollection' => false,
            'setting'=>$setting,
            'moneyFormat'=>$moneyFormat
        ]);

    }
}
