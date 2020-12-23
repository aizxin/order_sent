/* eslint-disable no-undef */
<template>
  <div class="navbar">
    <hamburger :is-active="sidebar.opened" class="hamburger-container" @toggleClick="toggleSideBar" />

    <breadcrumb class="breadcrumb-container" />

    <div class="right-menu">
      <el-dropdown class="avatar-container" trigger="click">
        <div class="avatar-wrapper">
          <img :src="avatar" class="user-avatar">
          <i class="el-icon-caret-bottom" />
          <span class="user-name">{{ name }}</span>
        </div>
        <el-dropdown-menu slot="dropdown" class="user-dropdown">
          <router-link to="/">
            <el-dropdown-item>
              首页
            </el-dropdown-item>
          </router-link>
          <el-dropdown-item>
            <span @click="dialogFormVisible = true">修改密码</span>
          </el-dropdown-item>
          <el-dropdown-item divided @click.native="logout">
            <span style="display:block;">退出:></span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
      <!-- 修改密码弹窗 -->
      <el-dialog title="密码修改" :visible.sync="dialogFormVisible">
        <el-form ref="passwordForm" class="small-space" :model="passwordForm" :rules="passwordFormRules" label-position="right" label-width="100px" style="width: 100%">
          <el-form-item label="原密码" prop="old_password">
            <el-input v-model="passwordForm.old_password" type="password" auto-complete="off" />
          </el-form-item>
          <el-form-item label="新密码" prop="password">
            <el-input v-model="passwordForm.password" type="password" auto-complete="off" />
          </el-form-item>
          <el-form-item label="重复新密码" prop="confirm_password">
            <el-input v-model="passwordForm.confirm_password" type="password" auto-complete="off" />
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="handlePwdModifySubmit(passwordForm)">确 定</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import Breadcrumb from '@/components/Breadcrumb'
import Hamburger from '@/components/Hamburger'

export default {
  components: {
    Breadcrumb,
    Hamburger
  },
  data() {
    const validateOldPassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('密码至少6字母或数字'))
      } else {
        callback()
      }
    }
    const validateNewPassword2 = (rule, value, callback) => {
      if (value !== this.passwordForm.password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      dialogVisible: false,
      dialogFormVisible: false,
      passwordForm: {
        'old_password': '',
        'password': '',
        'confirm_password': ''
      },
      passwordFormRules: {
        old_password: [
          { required: true, trigger: 'blur', message: '旧密码不能为空' },
          { required: true, trigger: 'blur', validator: validateOldPassword }
        ],
        password: [
          { required: true, trigger: 'blur', message: '新密码不能为空！' }
        ],
        confirm_password: [
          { required: true, trigger: 'blur', message: '重复密码不能为空！' },
          { required: true, trigger: 'blur', validator: validateNewPassword2 }
        ]
      }
    }
  },
  computed: {
    ...mapGetters([
      'sidebar',
      'avatar',
      'name'
    ])
  },
  methods: {
    toggleSideBar() {
      this.$store.dispatch('app/toggleSideBar')
    },
    handlePwdModifySubmit(formName) {
      this.$refs.passwordForm.validate(valid => {
        if (valid) {
          console.log(this.passwordForm)
        } else {
          console.log('error submit!!')
          return false
        }
      })
    },
    async logout() {
      await this.$store.dispatch('user/logout')
      this.$router.push(`/login?redirect=${this.$route.fullPath}`)
    }
  }
}
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);

  .hamburger-container {
    line-height: 46px;
    height: 100%;
    float: left;
    cursor: pointer;
    transition: background .3s;
    -webkit-tap-highlight-color:transparent;

    &:hover {
      background: rgba(0, 0, 0, .025)
    }
  }

  .breadcrumb-container {
    float: left;
  }

  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .right-menu-item {
      display: inline-block;
      padding: 0 8px;
      height: 100%;
      font-size: 18px;
      color: #5a5e66;
      vertical-align: text-bottom;

      &.hover-effect {
        cursor: pointer;
        transition: background .3s;

        &:hover {
          background: rgba(0, 0, 0, .025)
        }
      }
    }

    .avatar-container {
      margin-right: 30px;

      .avatar-wrapper {
        margin-top: 5px;
        position: relative;

        .user-avatar {
          cursor: pointer;
          width: 40px;
          height: 40px;
          border-radius: 10px;
        }

        .user-name {
          position: relative;
          top: -14px;
          margin-right: 25px;
          margin-left: 5px;
          font-weight: 600;
          cursor: pointer;
        }

        .el-icon-caret-bottom {
          cursor: pointer;
          position: absolute;
          right: 0px;
          top: 18px;
          font-size: 16px;
        }
      }
    }
  }
}
</style>
