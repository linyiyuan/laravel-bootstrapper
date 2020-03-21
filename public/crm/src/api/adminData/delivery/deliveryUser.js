import request from '@/utils/request'
export function deliveryUserList(params) {
  return request({
    url:'/admin_data/delivery/delivery_user',
    method:'get',
    params:params
  })
}

export function createDeliveryUser(params) {
  return request({
    url:'/admin_data/delivery/delivery_user',
    method:'post',
    data:params
  })
}

export function updateDeliveryUser(id, params) {
  return request({
    url:'/admin_data/delivery/delivery_user' + '/' + id,
    method:'put',
    data:params
  })
}

export function deleteDeliveryUser(id) {
  return request({
    url:'/admin_data/delivery/delivery_user' + '/' + id,
    method:'delete',
    data:id,
  })
}
