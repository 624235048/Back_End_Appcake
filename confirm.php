import 'dart:convert';

import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:ppsgasproject/model/detailshop_model.dart';
import 'package:ppsgasproject/model/order_model.dart';
import 'package:ppsgasproject/model/user_model.dart';
import 'package:ppsgasproject/utility/dialog.dart';
import 'package:ppsgasproject/utility/my_api.dart';
import 'package:ppsgasproject/utility/my_constant.dart';
import 'package:ppsgasproject/utility/my_style.dart';
import 'package:shared_preferences/shared_preferences.dart';

class OrderListShop extends StatefulWidget {
  @override
  _OrderListShopState createState() => _OrderListShopState();
}

class _OrderListShopState extends State<OrderListShop> {
  DetailShopModel detailShopModel;
  bool loadStatus = true; // Process load JSON
  bool status = true;
  List<OrderModel> ordermodels = List();
  List<List<String>> listnameGas = List();
  List<List<String>> listAmounts = List();
  List<List<String>> listPrices = List();
  List<List<String>> listSums = List();
  List<int> totals = List();
  List<List<String>> listusers = List();
  @override
  void initState() {
    // TODO: implement initState
    super.initState();
    findOrderShop();
    showContent();
  }

  Future<Null> findOrderShop() async {
    if (ordermodels.length != 0) {
      ordermodels.clear();
    }

    String path = '${MyConstant().domain}/gas/getOrderfromcart.php?isAdd=true';
    await Dio().get(path).then((value) {
      // print('value ==> $value');
      var result = jsonDecode(value.data);
      // print('result ==> $result');
      if (result != null) {
        for (var item in result) {
          OrderModel model = OrderModel.fromJson(item);
          // print('OrderdateTime ==> ${model.orderDateTime}');

          List<String> nameGas =
              MyAPI().createStringArray(model.gas_brand_name);
          List<String> amountgas = MyAPI().createStringArray(model.amount);
          List<String> pricegas = MyAPI().createStringArray(model.price);
          List<String> pricesums = MyAPI().createStringArray(model.sum);
          List<String> user_id = MyAPI().createStringArray(model.user_id);

          int total = 0;
          for (var item in pricesums) {
            total = total + int.parse(item);
          }

          setState(() {
            loadStatus = false;
            ordermodels.add(model);
            listnameGas.add(nameGas);
            listAmounts.add(amountgas);
            listPrices.add(pricegas);
            listSums.add(pricesums);
            totals.add(total);
            listusers.add(user_id);
          });
        }
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: <Widget>[
        loadStatus ? buildNoneOrder() : showContent(),
      ],
    );
  }

  Widget showContent() {
    return status ? showListOrderGas() : buildNoneOrder();
  }

  Center buildNoneOrder() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            height: 100,
            width: 100,
            child: Image.asset('assets/images/noneorder.png'),
          ),
          Text(
            'ยังไม่มีข้อมูลการสั่งแก๊ส',
            style: TextStyle(fontSize: 28),
          ),
        ],
      ),
    );
  }

  Future refresh() async {
    setState(() {
      findOrderShop();
    });
  }

  Widget showListOrderGas() {
    return RefreshIndicator(
      onRefresh: refresh,
      child: ListView.builder(
        itemCount: ordermodels.length,
        itemBuilder: (context, index) => Card(
          color: index % 2 == 0 ? Colors.grey.shade100 : Colors.grey.shade100,
          child: Padding(
            padding: const EdgeInsets.all(8.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                MyStyle().showTitleH2('คุณ ${ordermodels[index].user_name}'),
                MyStyle()
                    .showTitleH3('คำสั่งซื้อ : ${ordermodels[index].orderId}'),
                MyStyle().showTitleH3(
                    'เวลาสั่งซื้อ : ${ordermodels[index].orderDateTime}'),
                MyStyle().showTitleH3(
                    'เวลาสั่งซื้อ : ${ordermodels[index].pamentStatus}'),
                MyStyle().showTitleH3('สถานะการจัดส่ง : รอยืนยัน'),
                buildTitle(),
                ListView.builder(
                  itemCount: listnameGas[index].length,
                  shrinkWrap: true,
                  physics: ScrollPhysics(),
                  itemBuilder: (context, index2) => Container(
                    padding: EdgeInsets.all(5.0),
                    child: Row(
                      children: [
                        Expanded(
                          flex: 2,
                          child: Text(
                            '${listAmounts[index][index2]}x',
                            style: MyStyle().mainh3Title,
                          ),
                        ),
                        Expanded(
                          flex: 1,
                          child: Text(
                            listnameGas[index][index2],
                            style: MyStyle().mainh3Title,
                          ),
                        ),
                        Expanded(
                          flex: 1,
                          child: Text(
                            listPrices[index][index2],
                            style: MyStyle().mainh3Title,
                          ),
                        ),
                        Expanded(
                          flex: 1,
                          child: Text(
                            listSums[index][index2],
                            style: MyStyle().mainh3Title,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                Container(
                  padding: EdgeInsets.all(4.0),
                  child: Row(
                    children: [
                      Expanded(
                        flex: 6,
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.end,
                          children: [
                            Text(
                              'รวมทั้งหมด :  ',
                              style: MyStyle().mainh1Title,
                            ),
                          ],
                        ),
                      ),
                      Expanded(
                        flex: 2,
                        child: Text(
                          '${totals[index].toString()} THB',
                          style: MyStyle().mainhATitle,
                        ),
                      ),
                    ],
                  ),
                ),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: [
                    RaisedButton.icon(
                      color: Colors.red,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(30),
                      ),
                      onPressed: () async {
                        confirmDeleteCancleOrder(index);
                      },
                      icon: Icon(
                        Icons.cancel,
                        color: Colors.white,
                      ),
                      label: Text(
                        'Cancel',
                        style: TextStyle(color: Colors.white),
                      ),
                    ),
                    RaisedButton.icon(
                      color: Colors.green,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(30),
                      ),
                      onPressed: () async {
                        updateStatusConfirmOrder(index).then((value) {
                          normalDialog(
                              context, 'ส่งรายการแก๊สไปยังพนักงานแล้วค่ะ');
                          Navigator.pop(context);
                          findOrderShop();
                        });
                      },
                      icon: Icon(
                        Icons.check_circle,
                        color: Colors.white,
                      ),
                      label: Text(
                        'Confirm',
                        style: TextStyle(color: Colors.white),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Future<Null> cancleOrderUser(int index) async {
    String order_id = ordermodels[index].orderId;
    String url =
        '${MyConstant().domain}/gas/cancleOrderWhereorderId.php?isAdd=true&status=Cancle&order_id=$order_id';

    await Dio().get(url).then((value) {
      notificationCancleShop(index);
      findOrderShop();
      normalDialog2(
          context, 'ยกเลิกรายการสั่งซื้อสำเร็จ', 'รายการสั่งซื้อที่ $order_id');
    });
  }

  Future<Null> confirmDeleteCancleOrder(int index) async {
    showDialog(
      context: context,
      builder: (context) => SimpleDialog(
        title: Text(
            'คุณต้องการจะยกเลิกรายการแก๊สที่ ${ordermodels[index].orderId} ใช่ไหม ?'),
        children: <Widget>[
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: <Widget>[
              RaisedButton.icon(
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(30),
                ),
                color: Colors.green,
                onPressed: () async {
                  cancleOrderUser(index);

                  Navigator.pop(context);
                },
                icon: Icon(
                  Icons.check,
                  color: Colors.white,
                ),
                label: Text(
                  'ตกลง',
                  style: TextStyle(
                      color: Colors.white, fontWeight: FontWeight.bold),
                ),
              ),
              RaisedButton.icon(
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(30),
                ),
                color: Colors.red,
                onPressed: () {
                  Navigator.pop(context);
                },
                icon: Icon(
                  Icons.clear,
                  color: Colors.white,
                ),
                label: Text(
                  'ยกเลิก',
                  style: TextStyle(
                      color: Colors.white, fontWeight: FontWeight.bold),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Container buildTitle() {
    return Container(
      padding: EdgeInsets.all(4),
      decoration: BoxDecoration(color: Color.fromARGB(255, 11, 91, 128)),
      child: Row(
        children: [
          Expanded(
              flex: 2,
              child: Text(
                'จำนวน',
                style: MyStyle().mainh4Title,
              )),
          Expanded(
            flex: 1,
            child: Text(
              'ยี่ห้อ',
              style: MyStyle().mainh4Title,
            ),
          ),
          Expanded(
            flex: 1,
            child: Text(
              'ราคา',
              style: MyStyle().mainh4Title,
            ),
          ),
          Expanded(
            flex: 1,
            child: Text(
              'รวม',
              style: MyStyle().mainh4Title,
            ),
          ),
        ],
      ),
    );
  }

  Future<Null> updateStatusConfirmOrder(int index) async {
    String user_id = ordermodels[index].user_id;
    String path =
        '${MyConstant().domain}/gas/editStatusWhereuser_id.php?isAdd=true&status=shopprocess&user_id=$user_id';

    // SharedPreferences preferences = await SharedPreferences.getInstance();
    // String user_name = preferences.getString('Name');

    await Dio().get(path).then(
      (value) {
        if (value.toString() == 'true') {
          notificationtoShop(index);
          normalDialog(context, 'ส่งรายการแก๊สไปยังพนักงานแล้วค่ะ');
        }
      },
    );
  }

  Future<Null> notificationCancleShop(int index) async {
    String id = ordermodels[index].user_id;
    String urlFindToken =
        '${MyConstant().domain}/gas/getuserWhereChooseType.php?isAdd=true&id=$id';

    await Dio().get(urlFindToken).then((value) {
      var result = json.decode(value.data);
      print('result == $result');
      for (var json in result) {
        UserModel model = UserModel.fromJson(json);
        String tokenUser = model.token;
        print('tokenShop ==>> $tokenUser');
        String title = 'คุณ ${model.name} ขออภัยในความไม่สะดวก';
        String body =
            'ทางร้านได้ยกเลิกคำสั่งซื้อของคุณกรุณาติดต่อร้าน ขอบคุณค่ะ';

        String urlSendToken =
            '${MyConstant().domain}/gas/apiNotification.php?isAdd=true&token=$tokenUser&title=$title&body=$body';
        sendNotificationToShop(urlSendToken);
      }
    });
  }

  Future<Null> notificationtoShop(int index) async {
    String id = ordermodels[index].user_id;
    String urlFindToken =
        '${MyConstant().domain}/gas/getuserWhereChooseType.php?isAdd=true&id=$id';

    await Dio().get(urlFindToken).then((value) {
      var result = json.decode(value.data);
      print('result == $result');
      for (var json in result) {
        UserModel model = UserModel.fromJson(json);
        String tokenUser = model.token;
        print('tokenShop ==>> $tokenUser');
        String title =
            'คุณ ${model.name} ทางร้านได้ยืนยันการสั่งซื้อของคุณแล้ว';
        String body = 'กรุณารอรับสินค้าและตรวจสอบการสั่งซื้อ';

        String urlSendToken =
            '${MyConstant().domain}/gas/apiNotification.php?isAdd=true&token=$tokenUser&title=$title&body=$body';
        sendNotificationToShop(urlSendToken);
      }
    });
  }

  Future<Null> sendNotificationToShop(String urlSendToken) async {
    await Dio().get(urlSendToken).then(
          (value) => print('notification Success'),
        );
  }
}
