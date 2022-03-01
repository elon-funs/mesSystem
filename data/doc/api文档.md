API文档－2月22
=================================



## 创建（修改）订单 api/app/Production/add_porder 
------------------------------
# post

# 参数
- id  //订单id,修改必选同，创建时不传或传0
- user_name  //客户名
- name     //产品名称
- number     //排产数量 number 
- com_number //交付数量 com_number 
- com_time      //交货日期
- spec          //商品规格,文本输入
- production_id //产品工序组ID 从接口api/app/Production/get_pro　获取，下单时下拉选择且只能选父类为0的那个组pid。
- seller    //业务员名 文本输入
- remark    //备注



## 获取订单列表　api/app/pocart/order_list
--------------------------------
# get/post

# 参数
- id　订单id 可选　
- status　订单状态 可选　




## 订单详情及各工序详情查看与修改　api/app/pocart/detail
------------------------------
# 查看get
# 修改post

# 参数
- id 必选　订单Id
- pcid　修改时必填　订单关联工序的Id，一个订单下面的每个工序都有唯一pcid
- sum_num　修改时必填　该工序的完成数量
- cstatus　修改时必填　是否修改该工序的状态
- defective　　可选　不良品数量可默认为0　
- remark    可选



## 订单修改为完成状态并锁定　api/app/Production/order_finish
------------------------------
# get/post

# 参数
- id 必选　订单Id
- remark    可选


## 工序间交接，提交创建，修改，接受　api/app/pocart/trans_process
------------------------------
# post

# 参数
- id　修改、接受时必选，提交时不填　交接记录id，
- action:3 必填　1创建2修改3接受    	
- oid: 233 订单ID
- pid: 79, 可选产品工艺组ID
- sum_num: 90, 必填　交接数量
- sub_pcid: 425, 提交修改时必填　提交人的工序统计ID
- sub_remark: "xxx", 可选　提交人备注
- get_pcid: 426, 接受时必填　接收人的工序统计ID
- get_remark: " rrr" 可选　接收人备注
- get_proid:84 接受时必填　接受人的工序id

## 工序间交接记录查看　api/app/pocart/trans_log
------------------------------
# post

# 参数
- oid 必填　订单id
- pid　可选　工艺组id
- sub_pcid　可选　提交人工序统计id
- sub_id　可选　提交人ID
- get_id　可选　接收人ID
- get_pcid　可选　接收人工序统计id


## 查询待入库单　api/app/pocart/wait2store
------------------------------
# get/post

# 参数　无


## 入库操作　api/app/pocart/put2store
------------------------------
# post

# 参数　无
- oid　必传 订单id





