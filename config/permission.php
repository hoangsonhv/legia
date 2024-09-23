<?php 

return [

	'role_permission' => true,
	
	'permissions'     => [
		// 'dashboard' => [
		// 	'label' => 'Tổng quan',
		// 	'routes' => [
		// 		'admin.dashboard.index' => [
		// 			'label' => 'Tổng quan'
		// 		],
		// 	]
		// ],
        'co-tmp' => [
            'label' => 'Quản lý Chào Giá',
            'routes' => [
                'admin.co-tmp.index-all' => [
                    'label' => 'Tất cả chào giá'
                ],
                'admin.co-tmp.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.co-tmp.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.co-tmp.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.co-tmp.edit' => [
                    'label' => 'Hiển thị thông tin Chào Giá'
                ],
                'admin.co-tmp.update' => [
                    'label' => 'Cập nhật thông tin Chào Giá'
                ],
                'admin.co-tmp.destroy' => [
                    'label' => 'Xoá thông tin Chào Giá'
                ],
            ]
        ],
        'co' => [
            'label' => 'Quản lý CO',
            'routes' => [
                'admin.co.index-all' => [
                    'label' => 'Tất cả CO'
                ],
                'admin.co.show-price' => [
                    'label' => 'Xem đơn giá và thành tiền'
                ],
                'admin.co.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.co.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.co.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.co.edit' => [
                    'label' => 'Hiển thị thông tin CO'
                ],
                'admin.co.update' => [
                    'label' => 'Cập nhật thông tin CO'
                ],
                'admin.co.destroy' => [
                    'label' => 'Xoá thông tin CO'
                ],
                'admin.co.list-info-co' => [ // Route này chỉ phân quyền và không được định nghĩa
                    'label' => 'Hiển thị các phiếu CO'
                ],
//                'admin.co.delivery.save' => [
//                    'label' => 'Giao Nhận'
//                ],
            ]
        ],
        'request' => [
            'label' => 'Quản lý Phiếu Yêu Cầu',
            'routes' => [
                'admin.request.index-all' => [
                    'label' => 'Tất cả phiếu yêu cầu'
                ],
                'admin.request.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.request.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.request.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.request.edit' => [
                    'label' => 'Hiển thị Phiếu Yêu Cầu'
                ],
                'admin.request.update' => [
                    'label' => 'Cập nhật Phiếu Yêu Cầu'
                ],
                'admin.request.destroy' => [
                    'label' => 'Xoá Phiếu Yêu Cầu'
                ],
                'admin.request.update-survey-price' => [
                    'label' => 'Khảo sát giá'
                ],
            ]
        ],
        'price_survey' => [
            'label' => 'Quản lý khảo sát giá',
            'routes' => [
                'admin.price-survey.index-all' => [
                    'label' => 'Tất cả khảo sát giá'
                ],
                'admin.price-survey.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.price-survey.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.price-survey.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.price-survey.insert-multiple' => [
                    'label' => 'Lưu nhiều thông tin thêm mới'
                ],
                'admin.price-survey.edit' => [
                    'label' => 'Hiển thị thông tin khảo sát giá'
                ],
                'admin.price-survey.update' => [
                    'label' => 'Cập nhật thông tin khảo sát giá'
                ],
                'admin.price-survey.destroy' => [
                    'label' => 'Xoá thông tin khảo sát giá'
                ],
            ]
        ],
        'payment' => [
            'label' => 'Quản lý Phiếu Chi',
            'routes' => [
                'admin.payment.index-all' => [
                    'label' => 'Tất cả phiếu chi'
                ],
                'admin.payment.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.payment.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.payment.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.payment.edit' => [
                    'label' => 'Hiển thị Phiếu Chi'
                ],
                'admin.payment.update' => [
                    'label' => 'Cập nhật Phiếu Chi'
                ],
                'admin.payment.destroy' => [
                    'label' => 'Xoá Phiếu Chi'
                ],
            ]
        ],
        'receipt' => [
            'label' => 'Quản lý Phiếu Thu',
            'routes' => [
                'admin.receipt.index-all' => [
                    'label' => 'Tất cả phiếu thu'
                ],
                'admin.receipt.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.receipt.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.receipt.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.receipt.edit' => [
                    'label' => 'Hiển thị Phiếu Thu'
                ],
                'admin.receipt.update' => [
                    'label' => 'Cập nhật Phiếu Thu'
                ],
                'admin.receipt.destroy' => [
                    'label' => 'Xoá Phiếu Thu'
                ],
            ]
        ],
        'manufacture' => [
            'label' => 'Quản lý sản xuất',
            'routes' => [
                'admin.manufacture.confirm-quantity' => [
                    'label' => 'Xác nhận số lượng'
                ],
                'admin.manufacture.non-metal' => [
                    'label' => 'Phi kim loại'
                ],
                'admin.manufacture.metal' => [
                    'label' => 'Kim loại'
                ],
                'admin.manufacture.index-all' => [
                    'label' => 'Tất cả sản xuất'
                ],
                'admin.manufacture.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.manufacture.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.manufacture.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.manufacture.edit' => [
                    'label' => 'Hiển thị thông tin sản xuất'
                ],
                'admin.manufacture.update' => [
                    'label' => 'Cập nhật thông tin sản xuất'
                ],
                'admin.manufacture.destroy' => [
                    'label' => 'Xoá thông tin sản xuất'
                ],
                'admin.pdf.manufacture' => [
                    'label' => 'In lệnh sản xuất'
                ],
                'admin.pdf.manufacture-check' => [
                    'label' => 'In phiếu kiểm tra sản xuất'
                ]
            ]
        ],
        'delivery' => [
            'label' => 'Quản lý giao nhận',
            'routes' => [
                'admin.delivery.index-all' => [
                    'label' => 'Tất cả giao nhận'
                ],
                'admin.delivery.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.delivery.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.delivery.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.delivery.edit' => [
                    'label' => 'Hiển thị thông tin giao nhận'
                ],
                'admin.delivery.update' => [
                    'label' => 'Cập nhật thông tin giao nhận'
                ],
                'admin.delivery.destroy' => [
                    'label' => 'Xoá thông tin giao nhận'
                ],
            ]
        ],
        'warehouse-group' => [
            'label' => 'Quản lý nhóm hàng hóa',
            'routes' => [
                'admin.warehouse-group.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-group.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-group.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-group.edit' => [
                    'label' => 'Hiển thị thông tin nhóm hàng hóa'
                ],
                'admin.warehouse-group.update' => [
                    'label' => 'Cập nhật thông tin nhóm hàng hóa'
                ],
                'admin.warehouse-group.destroy' => [
                    'label' => 'Xoá thông tin nhóm hàng hóa'
                ]
            ]
        ],
        'warehouse-product-code' => [
            'label' => 'Quản lý mã hàng hóa',
            'routes' => [
                'admin.warehouse-product-code.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-product-code.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-product-code.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-product-code.edit' => [
                    'label' => 'Hiển thị thông tin mã hàng hóa'
                ],
                'admin.warehouse-product-code.update' => [
                    'label' => 'Cập nhật thông tin mã hàng hóa'
                ],
                'admin.warehouse-product-code.destroy' => [
                    'label' => 'Xoá thông tin mã hàng hóa'
                ],
                'admin.warehouse-product-code.import' => [
                    'label' => 'Import mã hàng hóa'
                ]
            ]
        ],
        'warehouse-export-sell' => [
            'label' => 'Quản lý Phiếu xuất kho bán hàng',
            'routes' => [
                'admin.warehouse-export-sell.index-all' => [
                    'label' => 'Tất cả phiếu xuất kho bán hàng'
                ],
                'admin.warehouse-export-sell.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-export-sell.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-export-sell.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-export-sell.edit' => [
                    'label' => 'Hiển thị thông tin phiếu xuất kho bán hàng'
                ],
                'admin.warehouse-export-sell.update' => [
                    'label' => 'Cập nhật thông tin phiếu xuất kho bán hàng'
                ],
                'admin.warehouse-export-sell.destroy' => [
                    'label' => 'Xoá thông tin phiếu xuất kho bán hàng'
                ],
                'admin.pdf.warehouse-export-sell' => [
                    'label' => 'In phiếu xuất kho bán hàng'
                ],
            ]
        ],
        'warehouse-export' => [
            'label' => 'Quản lý Phiếu xuất kho',
            'routes' => [
                'admin.warehouse-export.index-all' => [
                    'label' => 'Tất cả phiếu xuất kho'
                ],
                'admin.warehouse-export.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-export.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-export.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-export.edit' => [
                    'label' => 'Hiển thị thông tin phiếu xuất kho'
                ],
                'admin.warehouse-export.update' => [
                    'label' => 'Cập nhật thông tin phiếu xuất kho'
                ],
                'admin.warehouse-export.destroy' => [
                    'label' => 'Xoá thông tin phiếu xuất kho'
                ],
            ]
        ],
        'warehouse-receipt' => [
            'label' => 'Quản lý Phiếu nhập kho',
            'routes' => [
                'admin.warehouse-receipt.index-all' => [
                    'label' => 'Tất cả phiếu nhập kho'
                ],
                'admin.warehouse-receipt.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-receipt.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-receipt.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-receipt.edit' => [
                    'label' => 'Hiển thị thông tin phiếu nhập kho'
                ],
                'admin.warehouse-receipt.update' => [
                    'label' => 'Cập nhật thông tin phiếu nhập kho'
                ],
                'admin.warehouse-receipt.destroy' => [
                    'label' => 'Xoá thông tin phiếu nhập kho'
                ],
            ]
        ],
        'warehouse-group' => [
            'label' => 'Quản lý nhóm hàng hóa',
            'routes' => [
                'admin.warehouse-group.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-group.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-group.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-group.edit' => [
                    'label' => 'Hiển thị nhóm hàng hóa'
                ],
                'admin.warehouse-group.update' => [
                    'label' => 'Cập nhật nhóm hàng hóa'
                ],
                'admin.warehouse-group.destroy' => [
                    'label' => 'Xoá nhóm hàng hóa'
                ]
            ]
        ],
        'warehouse-plate' => [
            'label' => 'Quản lý Kho Tấm',
            'routes' => [
                'admin.warehouse-plate.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-plate.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-plate.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-plate.edit' => [
                    'label' => 'Hiển thị vật liệu'
                ],
                'admin.warehouse-plate.update' => [
                    'label' => 'Cập nhật vật liệu'
                ],
                'admin.warehouse-plate.destroy' => [
                    'label' => 'Xoá vật liệu'
                ],
                'admin.warehouse-plate.import' => [
                    'label' => 'Import dữ liệu'
                ],
            ]
        ],
        'warehouse-spw' => [
            'label' => 'Quản lý Kho SPW',
            'routes' => [
                'admin.warehouse-spw.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-spw.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-spw.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-spw.edit' => [
                    'label' => 'Hiển thị vật liệu'
                ],
                'admin.warehouse-spw.update' => [
                    'label' => 'Cập nhật vật liệu'
                ],
                'admin.warehouse-spw.destroy' => [
                    'label' => 'Xoá vật liệu'
                ],
                'admin.warehouse-spw.import' => [
                    'label' => 'Import dữ liệu'
                ],
            ]
        ],
        'warehouse-remain' => [
            'label' => 'Quản lý Kho Còn Lại',
            'routes' => [
                'admin.warehouse-remain.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.warehouse-remain.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.warehouse-remain.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.warehouse-remain.edit' => [
                    'label' => 'Hiển thị vật liệu'
                ],
                'admin.warehouse-remain.update' => [
                    'label' => 'Cập nhật vật liệu'
                ],
                'admin.warehouse-remain.destroy' => [
                    'label' => 'Xoá vật liệu'
                ],
                'admin.warehouse-remain.import' => [
                    'label' => 'Import dữ liệu'
                ],
            ]
        ],
		'administrator' => [
			'label' => 'Tài khoản',
			'routes' => [
				'admin.administrator.index' => [
					'label' => 'Danh sách'
				],
				'admin.administrator.create' => [
					'label' => 'Hiển thị mẫu thêm mới'
				],
				'admin.administrator.store' => [
					'label' => 'Lưu thông tin thêm mới'
				],
				// 'admin.administrator.meEdit' => [
				// 	'label' => 'Hiển thị thông tin cá nhân'
				// ],
				// 'admin.administrator.meUpdate' => [
				// 	'label' => 'Lưu thông tin cá nhân'
				// ],
				'admin.administrator.edit' => [
					'label' => 'Hiển thị thông tin 1 quản trị'
				],
				'admin.administrator.update' => [
					'label' => 'Cập nhật thông tin 1 quản trị'
				],
				'admin.administrator.destroy' => [
					'label' => 'Xoá thông tin quản trị'
				],
				'admin.administrator.resetPassword' => [
					'label' => 'Tạo mật khẩu mới cho quản trị'
				],
			]
		],
		'role' => [
			'label' => 'Phòng ban & quyền',
			'routes' => [
				'admin.role.index' => [
					'label' => 'Danh sách'
				],
				'admin.role.create' => [
					'label' => 'Hiển thị mẫu thêm mới'
				],
				'admin.role.store' => [
					'label' => 'Lưu thông tin thêm mới'
				],
				'admin.role.edit' => [
					'label' => 'Hiển thị phòng ban & quyền'
				],
				'admin.role.update' => [
					'label' => 'Cập nhật phòng ban & quyền'
				],
			]
		],
		'bank' => [
			'label' => 'Quản lý tài chính',
			'routes' => [
                'admin.bank.index-all' => [
                    'label' => 'Tất cả tài chính'
                ],
				'admin.bank.index' => [
					'label' => 'Danh sách'
				],
				'admin.bank.create' => [
					'label' => 'Hiển thị mẫu thêm mới'
				],
				'admin.bank.store' => [
					'label' => 'Lưu thông tin thêm mới'
				],
				'admin.bank.edit' => [
					'label' => 'Hiển thị thông tin tài chính'
				],
				'admin.bank.update' => [
					'label' => 'Cập nhật thông tin tài chính'
				],
				'admin.bank.destroy' => [
					'label' => 'Xoá thông tin tài chính'
				],
				'admin.bank.transaction' => [
					'label' => 'Giao dịch Nạp / Rút'
				],
			]
		],
        'bank-loans' => [
            'label' => 'Vay nợ',
            'routes' => [
                'admin.bank-loans.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.bank-loans.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.bank-loans.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.bank-loans.edit' => [
                    'label' => 'Hiển thị vay nợ'
                ],
                'admin.bank-loans.update' => [
                    'label' => 'Cập nhật vay nợ'
                ],
                'admin.bank-loans.destroy' => [
                    'label' => 'Xoá vay nợ'
                ],
                'admin.bank-loans.insert-detail' => [
                    'label' => 'Thanh toán vay nợ'
                ]
            ]
        ],
        'customer' => [
            'label' => 'Quản lý khách hàng',
            'routes' => [
                'admin.customer.index-all' => [
                    'label' => 'Tất cả khách hàng'
                ],
                'admin.customer.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.customer.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.customer.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.customer.edit' => [
                    'label' => 'Hiển thị thông tin khách hàng'
                ],
                'admin.customer.update' => [
                    'label' => 'Cập nhật thông tin khách hàng'
                ],
                'admin.customer.destroy' => [
                    'label' => 'Xoá thông tin khách hàng'
                ],
            ]
        ],
        'documents' => [
            'label' => 'Chứng từ',
            'routes' => [
                'admin.documents.index-all' => [
                    'label' => 'Tất cả chứng từ'
                ],
                'admin.documents.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.documents.create' => [
                    'label' => 'Hiển thị mẫu thêm mới'
                ],
                'admin.documents.store' => [
                    'label' => 'Lưu thông tin thêm mới'
                ],
                'admin.documents.edit' => [
                    'label' => 'Hiển thị chứng từ'
                ],
                'admin.documents.update' => [
                    'label' => 'Cập nhật chứng từ'
                ],
                'admin.documents.destroy' => [
                    'label' => 'Xoá chứng từ'
                ]
            ]
        ],
        'report' => [
            'label' => 'Thống kê',
            'routes' => [
                'admin.report.index' => [
                    'label' => 'Thống kê'
                ],
                'admin.report.tmp-co' => [
                    'label' => 'Thống kê chào giá'
                ],
                'admin.report.co' => [
                    'label' => 'Thống kê CO'
                ],
                'admin.report.request' => [
                    'label' => 'Thống kê phiếu yêu cầu'
                ],
                'admin.report.customer-tmp-co' => [
                    'label' => 'Thống kê KH - Chào giá'
                ],
                'admin.report.customer-co' => [
                    'label' => 'Thống kê KH - CO'
                ]
            ]
        ],
		'base-admin' => [
			'label' => 'Quản lý chung',
			'routes' => [
				'admin.base-admin.approval' => [
					'label' => 'Xét duyệt'
				],
                'admin.base-admin.check-warehouse' => [
                    'label' => 'Kiểm kho'
                ]
			]
		],
        'logadmin' => [
            'label' => 'Log hệ thống',
            'routes' => [
                'admin.logadmin.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.logadmin.show' => [
                    'label' => 'Hiển thị chi tiết'
                ],
            ]
        ],
        'config' => [
            'label' => 'Cấu hình chung',
            'routes' => [
                'admin.config.index' => [
                    'label' => 'Danh sách'
                ],
                'admin.config.store' => [
                    'label' => 'Lưu thông tin'
                ],
                'admin.config.price' => [
                    'label' => 'Thông tin đơn giá'
                ],
                'admin.history-active.index' => [
                    'label' => 'Lịch sử hoạt động'
                ],
            ]
        ],
	],
];
