<?
header("Content-type: application/x-javascript");

$obj = [
    'id_obj_8374739'=>[
        'op'=>[
            'status'=>1,
            'name'=>'name obj',
            'id'=>'56493034',
            'attach'=>[],
            'left'=>34,
            'top'=>100
        ],
        'values'=>[
            'value_name'=>[
                'value'=>'dsfd df gf g',
                'attach'=>[]
            ],
            'value_name2'=>[
                'value'=>'dsfd df gf g',
                'attach'=>[]
            ]
        ]
    ],
    'id_obj_577655'=>[
        'op'=>[
            'status'=>1,
            'name'=>'name obj',
            'id'=>'56493034',
            'attach'=>[],
            'left'=>240,
            'top'=>140
        ],
        'values'=>[
            'value_name'=>[
                'value'=>'dsfd df gf g',
                'attach'=>[]
            ],
            'value_name_fit'=>[
                'value'=>'dsfd df gf g',
                'attach'=>[]
            ]
        ]
    ],
    'id_obj_577646655'=>[
        'op'=>[
            'status'=>1,
            'name'=>'name obj',
            'id'=>'56493034',
            'attach'=>[],
            'left'=>440,
            'top'=>140
        ],
        'values'=>[
            'value_name'=>[
                'value'=>'dsfd df gf g',
                'attach'=>[]
            ],
            'value_name_fit'=>[
                'value'=>'dsfd df gf g',
                'attach'=>[]
            ]
        ]
    ]
];

$json = json_encode($obj);

?>
var buld_json_obj = <?=($json ? $json : '{}')?>;