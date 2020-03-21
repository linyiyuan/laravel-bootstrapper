<template> 
  <div class="app-container">
    <el-tabs v-model="activeRole"  @tab-click="handleClick" style="border-bottom: none">
      <el-tab-pane label="全部" name="0"></el-tab-pane>
      <el-tab-pane v-for="(item, key) in roles" key='key' :label="item" :name="key"></el-tab-pane>
    </el-tabs>
    <el-card class="filter-container" shadow="never">
      <div>
        <i class="el-icon-search"></i>
        <span>筛选搜索</span>
        <el-button style="float:right" type="primary" @click="handleSearchList()" size="small">
          查询搜索
        </el-button>
        <el-button style="float:right;margin-right: 15px" @click="handleResetSearch()" size="small">
          重置
        </el-button>
      </div>
      <div style="margin-top: 15px">
        <el-form :inline="true" :model="listQuery" size="small">
          <el-form-item label="账号搜索：">
            <el-input v-model="listQuery.username" class="input-width" placeholder="账号搜索："></el-input>
          </el-form-item>
          <el-form-item label="名称搜索：">
            <el-input v-model="listQuery.desc" class="input-width" placeholder="名称搜索："></el-input>
          </el-form-item>
          <el-form-item label="状态选择：">
            <el-select v-model="listQuery.status" class="input-width" placeholder="状态选择：">
                <el-option value=1 label="正常"></el-option>
                <el-option value=0 label="停用"></el-option>
            </el-select>
          </el-form-item>
        </el-form>
      </div>
    </el-card>
    <el-card class="operate-container" shadow="never">
      <i class="el-icon-tickets"></i>
      <span>数据列表</span>
      <el-button style="float: right;" icon="el-icon-plus" type="primary" size="mini" @click="handleAddUser">创建角色
      </el-button>
    </el-card>
    <div class="table-container">
      <el-table ref="userTable" :data="list" style="width: 100%;" size="mini" v-loading="listLoading">
        <el-table-column type="selection" width="60" align="center"></el-table-column>
        <el-table-column  label="用户头像" width="120" align="center">
          <template slot-scope="scope">
            <img style="width: 65px;height: 65px" :src="scope.row.avatar">
          </template>
        </el-table-column>
        <el-table-column sortable label="用户账号" prop="username" align="center">
          <template slot-scope="scope">{{scope.row.username}}</template>
        </el-table-column>
        <el-table-column  label="用户名称" width="150" align="center">
          <template slot-scope="scope">{{scope.row.desc}}</template>
        </el-table-column>
        <el-table-column  label="岗位" width="200" align="center">
          <template slot-scope="scope">
            <el-tag size="mini" v-for="(role, index) in scope.row.roles" :key="index">{{ role.description }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column sortable label="手机号码" width="140" align="center">
          <template slot-scope="scope">{{scope.row.mobile}}</template>
        </el-table-column>
        <el-table-column sortable label="状态" width="80" align="center">
          <template slot-scope="scope">{{scope.row.status | status}}</template>
        </el-table-column>
        <el-table-column sortable label="上次登录IP" width="140" align="center">
          <template slot-scope="scope">{{scope.row.last_ip}}</template>
        </el-table-column>
        <el-table-column sortable label="上次登录时间" width="200" align="center">
          <template slot-scope="scope">{{scope.row.last_login | formatLoginTime}}</template>
        </el-table-column>
        <el-table-column sortable label="创建时间" width="180" prop="last_login" align="center">
          <template slot-scope="scope">{{ scope.row.create_time | formatLoginTime}}</template>
        </el-table-column>
        <el-table-column label="操作" align="center" width="430">
          <template slot-scope="scope">
            <el-button icon="el-icon-edit" type="primary" size="mini" @click="handleEditUser(scope.$index, scope.row)">编辑</el-button>
            <el-button icon="el-icon-reset" type="success" size="mini" @click="handleViewPermission(scope.row)">权限设置</el-button>
            <el-button icon="el-icon-reset" type="info" size="mini" @click="handleViewResetPassword(scope.row)">重置密码</el-button>
            <el-button icon="el-icon-delete" type="danger" size="mini" @click="handleDeleteUser(scope.$index, scope.row)" v-show="scope.row.id != userId">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="total, sizes,prev, pager, next,jumper" :current-page.sync="listQuery.cur_page" :page-size="listQuery.page_size" :page-sizes="[5,10,15]" :total="total">
      </el-pagination>
    </div>
    <!-- 重置密码 -->
    <el-dialog title="重置密码" :visible.sync="resetPasswordDialogVisible" width="500px" :close-on-click-modal="false">
      <el-form ref="roleForm" :model="resetPasswordForm" label-width="80px">
        <el-form-item label="新密码">
          <el-input type="password" v-model="resetPasswordForm.new_password" auto-complete="off" size="medium"></el-input>
        </el-form-item>
        <el-form-item label="确认密码">
          <el-input type="password" v-model="resetPasswordForm.confirm_password" auto-complete="off" size="medium"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer">
        <el-button size="small" @click="resetPasswordDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleResetPassword()">确 定</el-button>
      </div>
    </el-dialog>
    <!-- 权限设置 -->
    <el-dialog title="权限设置" :visible.sync="permissonDialogVisible" width="35%" :close-on-click-modal="false">
      <el-input size="medium" placeholder="输入关键字进行过滤" v-model="filterText">
      </el-input>
      <el-divider></el-divider>
      <el-tree :data="dialogData.list" :props="dialogData.defaultProps" ref="tree" :current-node-key="dialogData.checkedList" node-key="name" :check-strictly="checkStrictly" :default-expanded-keys="dialogData.defaultCheckedList" :default-checked-keys="dialogData.defaultCheckedList" show-checkbox :filter-node-method="filterNode">
      </el-tree>
      <div slot="footer">
        <el-button size="small" type="warning" @click="checkStrictly = false">开启关联</el-button>
        <el-button size="small" @click="permissonDialogVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="handleAddUserPermission()">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import { userList, deleteUser, resetPassword, giveUserPermission } from '@/api/auth/user'
import { getPermission } from '@/api/auth/permission'
import { formatDate } from '@/utils/date';
import { getRole } from '@/api/auth/role'
import store from '@/store'
const defaultListQuery = {
  cur_page: 1,
  page_size: 15,
  role_name: null,
};
const defaultResetPasswordForm = {
  uid: null,
  old_password: null,
  new_password: null,
  confirm_password: null,
}
export default {
  name: "userList",
  components: {},
  data() {

    return {
      listQuery: Object.assign({}, defaultListQuery),
      listLoading: true,
      list: null,
      total: null,
      roles: null,
      operateType: null,
      activeRole: null,
      checkStrictly: true,
      resetPasswordDialogVisible: false,
      permissonDialogVisible: false,
      multipleSelection: [],
      closeOrder: {
        dialogVisible: false,
        content: null,
        orderIds: []
      },
      dialogData: {
        list: null,
        defaultProps: {
          children: 'children',
          label: 'display_name'
        },
        defaultCheckedList: null,
        checkedList: null,
        user_id: null,
      },
      resetPasswordForm: Object.assign({}, defaultResetPasswordForm),
      userId: store.getters.userId,
      filterText: '',
    }
  },
  created() {
    getRole({ type: 'tree' }).then(response => {
      this.roles = response.data
    });
    this.listQuery.role_name = this.activeRole
    this.getList();
  },
  watch: {
    filterText(val) {
      this.$refs.tree.filter(val);
    }
  },
  filters: {
    formatLoginTime(time) {
      let date = new Date(time * 1000);
      return formatDate(date, 'yyyy-MM-dd hh:mm:ss')
    },
    status(status) {
      if (status == 0) { return '停用'}
      if (status == 1) { return '正常'}
    }
  },
  methods: {
    filterNode(value, data) {
      if (!value) return true;
      return data.display_name.indexOf(value) !== -1;
    },
    handleViewPermission(row) {
      this.permissonDialogVisible = true;
      this.dialogData.defaultCheckedList = null
      this.gerPermission(row.id);
    },
    gerPermission(user_id) {
      this.checkStrictly = true;
      getPermission({ type: 'tree' }).then(response => {
        var list = response.data.list
        this.dialogData.list = list
      })

      getPermission({ user_id: user_id }).then(response => {
        var list = response.data.list
        this.$nextTick(() => {
          this.dialogData.defaultCheckedList = list
        })

      })
      this.dialogData.user_id = user_id
    },
    handleAddUserPermission() {
      var checkedKeys = this.$refs.tree.getCheckedKeys()
      var halfCheckedKeys = this.$refs.tree.getHalfCheckedKeys()

      var checkedPermission = checkedKeys.concat(halfCheckedKeys)

      var postData = { userId: this.dialogData.user_id, permissionsAllow: checkedPermission }
      giveUserPermission({ postData: postData }).then(response => {
        this.permissonDialogVisible = false
      })
    },
    handleViewResetPassword(data) {
      this.resetPasswordForm.uid = data.id
      this.resetPasswordDialogVisible = true;
    },
    handleResetPassword() {
      resetPassword({ postData: this.resetPasswordForm }).then(response => {
        this.getList();
        this.resetPasswordForm = Object.assign({}, defaultResetPasswordForm)
        this.resetPasswordDialogVisible = false;
      });
    },
    handleResetSearch() {
      this.listQuery = Object.assign({}, defaultListQuery);
    },
    handleSearchList() {
      this.listQuery.cur_page = 1;
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
    handleCurrentChange(val) {
      this.listQuery.cur_page = val;
      this.getList();
    },
    getList() {
      this.listLoading = true;
      userList(this.listQuery).then(response => {
        this.listLoading = false;
        this.list = response.data.list;
        this.total = response.data.total;
      });
    },
    handleAddUser() {
      this.$router.push({ path: '/auth/user/create' });
    },
    handleEditUser(index, row) {
      this.$router.push({ path: '/auth/user/update', query: { id: row.id } });
    },
    deleteUser(id) {
      this.$confirm('是否要进行该删除操作?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteUser(id).then(response => {
          this.getList();
        });
      })
    },
    handleClick() {
      this.listQuery.role_name = this.activeRole
      this.getList();
    }
  }
}

</script>
<style scoped>
.input-width {
  width: 203px;
}

</style>
