<?php
return [
	[
		'columns' => [
			[
				'attribute' => 'union_code',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'plant_code',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
	[
		'columns' => [
			[
				'attribute' => 'mcc_plant_code',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'bmc_code',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
	[
		'columns' => [
			[
				'attribute' => 'end_point',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'request_url',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
	[
		'columns' => [
			[
				'attribute' => 'date1',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'date2',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
	[
		'columns' => [
			[
				'attribute' => 'desc1',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'desc2',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
	[
		'columns' => [
			[
				'attribute' => 'status_type',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'status_response',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
    [
		'columns' => [
			[
				'attribute' => 'request_desc',
				'valueColOptions' => ['style' => 'width:30%'],
			],
			[
				'attribute' => 'txn_type',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
    [
		'columns' => [
			[
				'attribute' => 'response_timestamp',
				'valueColOptions' => ['style' => 'width:30%'],
			],
            [
				'attribute' => 'request_timestamp',
				'valueColOptions' => ['style' => 'width:30%'],
			],
		],
	],
    [
		'columns' => [
			[
				'attribute' => 'status_code',
				'valueColOptions' => ['style' => 'width:80%'],
			],
		],
	],
    [
        'columns' => [
            [
                'attribute' => 'status_message',
                'valueColOptions' => [
                    'style' => 'width:80%; max-width:90%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;'
                ],
            ],
        ],
    ],
    [
		'columns' => [
			[
				'attribute' => 'request_header',
				'valueColOptions' => [
                    'style' => 'width:80%; max-width:90%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;'
                ],
			],
		],
	],
    [
		'columns' => [
			[
				'attribute' => 'request_payload',
				'valueColOptions' => [
                    'style' => 'width:80%; max-width:90%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;'
                ],
			],
		],
	],
    [
		'columns' => [
			[
				'attribute' => 'response_payload',
				'valueColOptions' => [
                    'style' => 'width:80%; max-width:90%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;'
                ],
			],
		],
	],
];
?>