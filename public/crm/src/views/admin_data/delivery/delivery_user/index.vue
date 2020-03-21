<template>
  <div class="app-container">
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
        <el-button icon="el-icon-plus" type="primary" size="mini" style="float: right; margin-left: 10px" @click="handleViewDeliveryUser('add', '')">添加
        </el-button>
        <el-button icon="el-icon-view" type="success" size="mini" style="float: right;" @click="handleViewDeliveryUserGroup()">投放组
        </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="DeliveryUserTable" :data="list" style="width: 100%;" v-loading="listLoading" size="mini">
        <el-table-column type="selection" width="60" align="center"></el-table-column>
        <el-table-column sortable label="ID" prop="id" width="80" align="center">
          <template slot-scope="scope">{{scope.row.id}}</template>
        </el-table-column>
        <el-table-column sortable label="投放人员" prop="name" align="center">
          <template slot-scope="scope">{{scope.row.name}}</template>
        </el-table-column>
        <el-table-column label="投放小组" align="center">
          <template slot-scope="scope">{{scope.row.group_name}}</template>
        </el-table-column>
        <el-table-column sortable label="排序" align="center">
          <template slot-scope="scope">
            {{ scope.row.orderby}}
          </template>
        </el-table-column>
        <el-table-column sortable label="包含活动数量" align="center">
          <template slot-scope="scope">
            0
          </template>
        </el-table-column>
        <el-table-column sortable label="状态" prop="status" width="100" align="center">
          <template slot-scope="scope">{{ scope.row.status | status}}</template>
        </el-table-column>
        <el-table-column label="操作" width="300" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleViewDeliveryUser('edit',scope.row)">编辑</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="deleteDeliveryUser(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15,30,50,100]" :total="total">
      </el-pagination>
    </div>
    <!-- 添加/修改投放人员 -->
    <!--  <el-dialog title="添加投放人员" :visible.sync="DeliveryUserDialogVisible" width="900px" :close-on-click-modal="false">
      <el-form ref="DeliveryUserForm" :model="DeliveryUserForm" :rules="DeliveryUserRules" label-width="80px">
        <el-form-item label="选择渠道">
          <el-select v-model="DeliveryUserForm.pid" size="medium" @change="changePid" placeholder="请选择所属渠道">
            <el-option v-for="(item, key) in pids" :key="key" :label="item" :value="key">
            </el-option>
          </el-select>
          <el-select v-model="DeliveryUserForm.channel_id" class="input-width" placeholder="全部" clearable v-if="channel_list != false">
            <el-option v-for="(channel_name, channel_id) in channel_list" :key="channel_id" :label="channel_name" :value="channel_id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="系统类型">
          <el-select v-model="DeliveryUserForm.osType" class="input-width" placeholder="全部">
            <el-option label="安卓" :value="1"></el-option>
            <el-option label="ios" :value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="内容" prop="content">
          <el-card shadow="never" style="width: 700px;padding:20px">
            <el-form-item style="margin-bottom: 10px"  v-for="(item, index) in DeliveryUserForm.content" :key="index">
                <el-input v-model="item.key" size="medium" placeholder="请填写KEY" style="width:250px"></el-input> --
                <el-input v-model="item.value" size="medium" placeholder="请填写VALUE" style="width:250px"></el-input>
                <el-button type="danger" size="medium" @click="removeItem(item)">删除</el-button>
            </el-form-item>
            <el-button type="primary" size="medium" style="margin-left: 50px" @click="addItem">增加道具</el-button>
           </el-card>
        </el-form-item>
        <el-form-item label="状态">
          <el-radio-group v-model="DeliveryUserForm.status">
            <el-radio :label="1">正常</el-radio>
            <el-radio :label="2">禁用</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="DeliveryUserDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleAddDeliveryUser()">确 定</el-button>
      </div>
    </el-dialog> -->
    <!-- 添加/修改投放人员 -->
    <el-dialog title="投放组管理" :visible.sync="deliveryUserGroupDialogVisible" width="700px" :close-on-click-modal="false">
      <el-card class="operate-container" shadow="never">
        <i class="el-icon-tickets"></i>
        <span>数据列表</span>
        <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleViewAddDeliveryUserGroup('add', '')">添加投放组
        </el-button>
      </el-card>
      <el-table ref="DeliveryUserTable" :data="groupList" style="width: 100%;" v-loading="listLoading" size="mini">
        <el-table-column type="selection" width="60" align="center"></el-table-column>
        <el-table-column sortable label="ID" prop="id" width="80" align="center">
          <template slot-scope="scope">{{scope.row.id}}</template>
        </el-table-column>
        <el-table-column sortable label="投放组名" prop="name" align="center">
          <template slot-scope="scope">{{scope.row.name}}</template>
        </el-table-column>
        <el-table-column label="排序" align="center">
          <template slot-scope="scope">{{scope.row.orderby}}</template>
        </el-table-column>
        <el-table-column label="操作" width="300" align="center">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleViewDeliveryUser('edit',scope.row)">编辑</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="deleteDeliveryUser(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="pagination-container">
        <el-pagination background @size-change="handleGroupSizeChange" @current-change="handleGroupCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15,30,50,100]" :total="groupTotal">
        </el-pagination>
      </div>
      <div slot="footer">
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { deliveryUserList, } from '@/api/adminData/delivery/deliveryUser';
import { deliveryUserGroupList, } from '@/api/adminData/delivery/deliveryUserGroup';
import { formatDate } from '@/utils/date';
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
};
export default {
  name: "deliveryUser",
  data() {
    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: null,
      groupList: null,
      total: null,
      groupTotal: null,
      multipleSelection: [],
      selectDialogVisible: false,
      deliveryUserDialogVisible: false,
      deliveryUserGroupDialogVisible: false,
      deliveryUserForm: {
        id: null,
      },
      DeliveryUserRules: {
        content: [
          { required: true, message: '请填写内容', trigger: 'blur' }
        ]
      },
    }
  },
  created() {
    this.getList();
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
    status(status) {
      if (status == 1) return '停用';
      if (status == 0) { return '正常' }
    }
  },
  methods: {
    handleViewDeliveryUserGroup() {
      this.getDeliveryUserGroupList();
      this.deliveryUserGroupDialogVisible = true
    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
      this.getList();
    },
    handleSearchList() {
      this.listQuery.pageNum = 1;
      this.getList();
    },
    handleDeleteUser(index, row) {
      this.deleteUser(row.id);
    },
    handleSizeChange(val) {
      this.listQuery.cur_page = 1;
      this.listQuery.page_size = val;
      this.getList();
    },
    handleGroupSizeChange(val) {
      this.listQuery.cur_page = 1;
      this.listQuery.page_size = val;
      this.getGroupList();
    },
    handleCurrentChange(val) {
      this.listQuery.cur_page = val;
      this.getList();
    },
    handleGroupCurrentChange(val) {
      this.listQuery.cur_page = val;
      this.getGroupList();
    },
    getList() {
      this.listLoading = true;
      deliveryUserList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    getDeliveryUserGroupList() {
      deliveryUserGroupList(this.listQuery).then(response => {
        this.groupList = response.data.list;
        this.groupTotal = response.data.total;
      });
    },
    handleViewDeliveryUser(type, data) {
      this.DeliveryUserDialogVisible = true
      this.type = type
      if (type == 'edit') {
        this.DeliveryUserForm.id = data.id
        this.DeliveryUserForm.pid = data.pid
        this.DeliveryUserForm.channel_id = data.channel_id
        this.DeliveryUserForm.osType = data.osType
        this.DeliveryUserForm.status = data.status
        this.DeliveryUserForm.content = data.content;
      } else {
        this.DeliveryUserForm.id = null
        this.DeliveryUserForm.pid = '0'
        this.DeliveryUserForm.channel_id = null
        this.DeliveryUserForm.osType = 1
        this.DeliveryUserForm.status = 2
        this.DeliveryUserForm.content = [{
          key: '',
          value: ''
        }]
      }
    },
    handleAddDeliveryUser() {
      this.$refs['DeliveryUserForm'].validate((valid) => {
        if (valid) {
          if (this.type == 'add') {
            createDeliveryUser({ postData: this.DeliveryUserForm }).then(response => {
              if (response.errorCode == 200) {
                this.$message({
                  message: response.data,
                  type: 'success',
                  duration: 1000
                });
                this.getList();
              } else {
                this.$message({
                  message: response.data,
                  type: 'success',
                  duration: 1000
                });
              }
            })
          } else {
            updateDeliveryUser(this.DeliveryUserForm.id, { postData: this.DeliveryUserForm }).then(response => {
              if (response.errorCode == 200) {
                this.$message({
                  message: response.data,
                  type: 'success',
                  duration: 1000
                });
                this.getList();
              } else {
                this.$message({
                  message: response.data,
                  type: 'success',
                  duration: 1000
                });
              }
            })
          }

        } else {
          this.$message({
            message: '表单验证不通过',
            type: 'success',
            duration: 1000
          });
          return false;
        }
      });
      this.DeliveryUserDialogVisible = false
    },
    deleteDeliveryUser(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        delDeliveryUser(id).then(response => {
          this.$message({
            message: '删除成功！',
            type: 'success',
            duration: 1000
          });
          this.getList();
        });
      })
    },
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
