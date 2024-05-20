/*
 Navicat Premium Data Transfer

 Source Server         : demo
 Source Server Type    : MySQL
 Source Server Version : 80035
 Source Host           : 120.79.38.186:3306
 Source Schema         : demo

 Target Server Type    : MySQL
 Target Server Version : 80035
 File Encoding         : 65001

 Date: 13/05/2024 21:18:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_config
-- ----------------------------
DROP TABLE IF EXISTS `admin_config`;
CREATE TABLE `admin_config`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `is_show` smallint(0) NULL DEFAULT 0 COMMENT '是否返回给公用接口 1显示0不显示',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_config
-- ----------------------------
INSERT INTO `admin_config` VALUES (2, 'captcha', 1, NULL, NULL, '2024-01-10 21:37:39');
INSERT INTO `admin_config` VALUES (6, 'token_expiration', 0, '86400', NULL, '2024-01-10 21:37:39');
INSERT INTO `admin_config` VALUES (18, 'mail_enable', 0, '1', NULL, '2024-03-05 16:35:19');
INSERT INTO `admin_config` VALUES (19, 'mail_config', 0, '{\"transport\":\"stmp\",\"host\":\"smtp.163.com\",\"port\":465,\"encryption\":\"ssl\",\"username\":\"chinaggood@163.com\",\"password\":\"MUE\",\"from_address\":\"c\"}', NULL, '2024-03-05 16:35:19');

-- ----------------------------
-- Table structure for admin_department
-- ----------------------------
DROP TABLE IF EXISTS `admin_department`;
CREATE TABLE `admin_department`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '部门名称',
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int(0) NULL DEFAULT 0 COMMENT '上级id',
  `leader` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '部门负责人',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_department
-- ----------------------------
INSERT INTO `admin_department` VALUES (1, '浙江分公司', '2024-01-10 16:35:37', 0, '老王', NULL);
INSERT INTO `admin_department` VALUES (3, '项目部', '2024-01-10 16:36:33', 1, '老李,老王', NULL);
INSERT INTO `admin_department` VALUES (4, '研发部', '2024-01-10 16:36:59', 1, '老温', NULL);
INSERT INTO `admin_department` VALUES (5, '技术维护部', '2024-01-10 16:37:30', 1, '暂无', NULL);

-- ----------------------------
-- Table structure for admin_login_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_login_log`;
CREATE TABLE `admin_login_log`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发生的时间',
  `ip` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `state` smallint(0) NULL DEFAULT NULL COMMENT '1登陆成功 0登陆失败',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 208 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `state` smallint(0) NULL DEFAULT 1 COMMENT '是非禁用 0禁用 1正常',
  `department_id` int(0) NULL DEFAULT NULL COMMENT '部门id',
  `role_id` int(0) NULL DEFAULT NULL COMMENT '角色',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '头像框',
  `phone_number` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (12, 'admin', '75cbbe32ae68e7fc23dd598a1c75ba7b', '2024-05-13 17:33:04', '2024-05-13 17:33:04', 1, 1, 1, 'admin', '/avatars/1.png', '13800138000');

-- ----------------------------
-- Table structure for admin_user_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_role`;
CREATE TABLE `admin_user_role`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '角色名',
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp(0) NULL DEFAULT NULL COMMENT '修改时间',
  `access_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '权限所有的id',
  `access_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT 'code码',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_role
-- ----------------------------
INSERT INTO `admin_user_role` VALUES (1, '超级管理员', '2024-01-08 17:26:48', '2024-05-12 16:44:06', '59,60,61,62,64,65,66,67,76,78,79,80,84,85,86,88,90,92,93,95,100,103,104,106,107,108,109,10,116,117,120,121,119,123,124,125,126,128,129,130,138,139,140,141,142,143,144,145,152,153,154,155,156,157,158,159,160,162,163,164,165,166,167,168,169', 'SYSTEM_ROLE_VIEW,SYSTEM_ROLE_CREATE,SYSTEM_ROLE_UPDATE,SYSTEM_ROLE_DELETE,SYSTEM_ACCESS_VIEW,SYSTEM_ACCESS_CREATE,SYSTEM_ACCESS_UPDATE,SYSTEM_ACCESS_DELETE,SYSTEM_DEPARTMENT_VIEW,SYSTEM_DEPARTMENT_UPDATE,SYSTEM_DEPARTMENT_CREATE,SYSTEM_DEPARTMENT_DELETE,SYSTEM_EMPLOYEE_VIEW,SYSTEM_EMPLOYEE_CREATE,SYSTEM_EMPLOYEE_UPDATE,,SYSTEM_LOG_VIEW,SYSTEM_CONFIG_VIEW,SYSTEM_CONFIG_CREATE,SYSTEM_CONFIG_UPDATE,SYSTEM_CONFIG_DELETE,SITE_MENU_VIEW,SITE_MENU_CREATE,SITE_MENU_UPDATE,SITE_MENU_DELETE,,,USER_VIEW,USER_HISTORY_VIEW,USER_HISTORY_LOGIN,USER_HISTORY_LIKE,USER_HISTORY_SMS,USER_COMMENT_VIEW,SITE_PAGE_VIEW,SITE_PAGE_UPDATE,SITE_PAGE_CREATE,SITE_PAGE_DELETE,SITE_ITERATION_VIEW,SITE_ITERATION_CREATE,SITE_ITERATION_UPDATE,,,,,,,,,CHAT_ASSISTANT_VIEW,CHAT_ORDER_VIEW,CHAT_MODEL_VIEW,,,,,,,CHAT_PLAN_VIEW,,,,,,,');
INSERT INTO `admin_user_role` VALUES (4, '对话管理', '2024-01-10 15:46:59', '2024-05-11 15:03:06', '152,155,165,166,167,153,154,156,157,158,159,162,163,164', 'CHAT_ASSISTANT_VIEW,,,,,CHAT_ORDER_VIEW,CHAT_MODEL_VIEW,,,,,CHAT_PLAN_VIEW,,');

-- ----------------------------
-- Table structure for admin_user_role_access
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_role_access`;
CREATE TABLE `admin_user_role_access`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '权限名称',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '权限描述',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '唯一权限码',
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '路径',
  `parent_id` int(0) NULL DEFAULT 0 COMMENT '父id',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 170 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_role_access
-- ----------------------------
INSERT INTO `admin_user_role_access` VALUES (1, '系统设置', '系统设置显示', 'SYSTEM', '2024-01-08 18:08:40', '2024-01-09 10:46:31', '/api/admin/system/config/list', 0);
INSERT INTO `admin_user_role_access` VALUES (4, '新增权限', '增加新的权限操作', 'xinzengquanxian', '2024-01-09 16:21:28', NULL, '/create', 3);
INSERT INTO `admin_user_role_access` VALUES (5, '修改权限', '修改', 'xiugai', '2024-01-09 16:22:02', NULL, '/update', 3);
INSERT INTO `admin_user_role_access` VALUES (8, '用户管理', '用户', 'USER', '2024-01-09 16:24:01', '2024-01-17 13:54:57', NULL, 0);
INSERT INTO `admin_user_role_access` VALUES (9, '用户列表', '用户list', 'USER_LIST', '2024-01-09 16:24:41', '2024-01-17 14:27:32', NULL, 8);
INSERT INTO `admin_user_role_access` VALUES (10, '查询', NULL, 'USER_VIEW', '2024-01-09 16:25:23', '2024-01-17 14:27:24', '/api/admin/user/list.get', 9);
INSERT INTO `admin_user_role_access` VALUES (19, '标签列表', '空', NULL, '2024-01-09 17:13:47', '2024-01-17 14:09:34', NULL, 18);
INSERT INTO `admin_user_role_access` VALUES (20, '查询', '查询列表', 'TOOL_TAG_VIEW', '2024-01-09 17:14:41', '2024-01-17 14:02:00', '/api/admin/tag/list.get', 19);
INSERT INTO `admin_user_role_access` VALUES (21, '新增标签', NULL, 'TOOL_TAG_CREATE', '2024-01-09 17:15:54', '2024-01-17 14:02:08', '/api/admin/tag.post', 19);
INSERT INTO `admin_user_role_access` VALUES (22, '修改标签', NULL, 'TOOL_TAG_UPDATE', '2024-01-09 17:16:21', '2024-01-17 14:02:13', '/api/admin/tag.put', 19);
INSERT INTO `admin_user_role_access` VALUES (23, '删除标签', '空', 'TOOL_TAG_DELETE', '2024-01-09 17:16:38', '2024-01-17 14:02:19', '/api/admin/tag.delete', 19);
INSERT INTO `admin_user_role_access` VALUES (29, NULL, NULL, 'POST1', '2024-01-09 17:25:09', '2024-01-09 09:25:09', NULL, NULL);
INSERT INTO `admin_user_role_access` VALUES (30, '工具列表', '空', NULL, '2024-01-09 17:25:34', '2024-01-09 09:25:34', NULL, 18);
INSERT INTO `admin_user_role_access` VALUES (37, '查询工具', NULL, 'TOOL_VIEW', '2024-01-09 17:27:38', '2024-01-17 14:09:20', '/api/admin/tool/list.get', 30);
INSERT INTO `admin_user_role_access` VALUES (42, '创建工具', '空', 'TOOL_CREATE', '2024-01-09 17:29:36', '2024-01-17 14:00:36', '/api/admin/tool.post', 30);
INSERT INTO `admin_user_role_access` VALUES (44, '修改工具', '空', 'TOOL_UPDATE', '2024-01-09 17:31:15', '2024-01-17 14:00:48', '/api/admin/tool.put', 30);
INSERT INTO `admin_user_role_access` VALUES (45, '删除工具', '空', 'TOOL_DELETE', '2024-01-09 17:31:28', '2024-01-17 14:01:03', '/api/admin/tool.delete', 30);
INSERT INTO `admin_user_role_access` VALUES (46, '栏目管理', '空', 'POST_COLUMN', '2024-01-09 17:34:54', '2024-01-09 09:34:54', NULL, 26);
INSERT INTO `admin_user_role_access` VALUES (47, '查询', '空', 'POST_COLUMN_VIEW', '2024-01-09 17:35:09', '2024-01-17 14:03:57', '/api/admin/column/list.get', 46);
INSERT INTO `admin_user_role_access` VALUES (48, '创建', '空', 'POST_COLUMN_CREATE', '2024-01-09 17:38:42', '2024-01-17 14:04:05', '/api/admin/column.get', 46);
INSERT INTO `admin_user_role_access` VALUES (49, '更新', '空', 'POST_COLUMN_UPDATE', '2024-01-09 17:39:06', '2024-01-17 14:04:15', '/api/admin/column.post', 46);
INSERT INTO `admin_user_role_access` VALUES (50, '删除', '空', 'POST_COLUMN_DELETE', '2024-01-09 17:39:20', '2024-01-17 14:04:24', '/api/admin/column.delete', 46);
INSERT INTO `admin_user_role_access` VALUES (51, '文章列表', '空', 'POST_LIST', '2024-01-09 17:39:48', '2024-01-09 09:39:48', NULL, 26);
INSERT INTO `admin_user_role_access` VALUES (52, '查询', '空', 'POST_VIEW', '2024-01-09 17:40:07', '2024-01-17 14:05:03', '/api/admin/post/list.get', 51);
INSERT INTO `admin_user_role_access` VALUES (53, '创建', '空', 'POST_CREATE', '2024-01-09 17:40:37', '2024-01-17 14:05:18', '/api/admin/post.post', 51);
INSERT INTO `admin_user_role_access` VALUES (54, '更新', '空', 'POST_UPDATE', '2024-01-09 17:40:52', '2024-01-17 14:05:27', '/api/admin/post.put', 51);
INSERT INTO `admin_user_role_access` VALUES (55, '删除', '空', 'POST_DELETE', '2024-01-09 17:41:10', '2024-01-17 14:05:36', '/api/admin/post.delete', 51);
INSERT INTO `admin_user_role_access` VALUES (56, '角色管理', '空', 'SYSTEM_ROLE', '2024-01-09 18:44:07', '2024-01-09 10:44:07', '', 1);
INSERT INTO `admin_user_role_access` VALUES (59, '查询', '空', 'SYSTEM_ROLE_VIEW', '2024-01-09 19:16:44', '2024-01-09 11:16:44', '/api/admin/system/role/list.get', 56);
INSERT INTO `admin_user_role_access` VALUES (60, '创建', NULL, 'SYSTEM_ROLE_CREATE', '2024-01-09 19:16:58', '2024-01-09 11:16:58', '/api/admin/system/role.post', 56);
INSERT INTO `admin_user_role_access` VALUES (61, '修改', '空', 'SYSTEM_ROLE_UPDATE', '2024-01-09 19:17:14', '2024-01-09 11:17:14', '/api/admin/system/role.put', 56);
INSERT INTO `admin_user_role_access` VALUES (62, '删除', '空', 'SYSTEM_ROLE_DELETE', '2024-01-09 19:17:33', '2024-01-09 11:17:33', '/api/admin/system/role.delete', 56);
INSERT INTO `admin_user_role_access` VALUES (63, '权限管理', '空', 'SYSTEM_ACCESS', '2024-01-09 19:22:07', '2024-01-09 11:22:07', NULL, 1);
INSERT INTO `admin_user_role_access` VALUES (64, '查询', '空', 'SYSTEM_ACCESS_VIEW', '2024-01-09 19:22:26', '2024-01-09 11:22:25', '/api/admin/system/access/list.get', 63);
INSERT INTO `admin_user_role_access` VALUES (65, '创建', '空', 'SYSTEM_ACCESS_CREATE', '2024-01-09 19:22:43', '2024-01-09 11:22:43', '/api/admin/system/access.post', 63);
INSERT INTO `admin_user_role_access` VALUES (66, '更新', '空', 'SYSTEM_ACCESS_UPDATE', '2024-01-09 19:22:59', '2024-01-09 11:22:59', '/api/admin/system/access.put', 63);
INSERT INTO `admin_user_role_access` VALUES (67, '删除', '空', 'SYSTEM_ACCESS_DELETE', '2024-01-09 19:23:12', '2024-01-11 10:50:02', '/api/admin/system/access.delete', 63);
INSERT INTO `admin_user_role_access` VALUES (75, '部门管理', '空', NULL, '2024-01-10 16:56:09', '2024-01-10 08:56:09', NULL, 1);
INSERT INTO `admin_user_role_access` VALUES (76, '查询', NULL, 'SYSTEM_DEPARTMENT_VIEW', '2024-01-10 16:56:44', '2024-01-11 11:08:50', '/api/admin/system/department/list.get', 75);
INSERT INTO `admin_user_role_access` VALUES (78, '修改', NULL, 'SYSTEM_DEPARTMENT_UPDATE', '2024-01-10 16:57:56', '2024-01-11 12:50:02', '/api/admin/system/department.put', 75);
INSERT INTO `admin_user_role_access` VALUES (79, '创建', '空', 'SYSTEM_DEPARTMENT_CREATE', '2024-01-10 16:59:24', '2024-01-11 12:49:52', '/api/admin/system/department.post', 75);
INSERT INTO `admin_user_role_access` VALUES (80, '删除', '空', 'SYSTEM_DEPARTMENT_DELETE', '2024-01-10 16:59:37', '2024-01-11 12:49:44', '/api/admin/system/department.delete', 75);
INSERT INTO `admin_user_role_access` VALUES (83, '员工管理', NULL, NULL, '2024-01-10 18:29:37', '2024-01-10 10:29:37', NULL, 1);
INSERT INTO `admin_user_role_access` VALUES (84, '查询', '空', 'SYSTEM_EMPLOYEE_VIEW', '2024-01-10 18:30:14', '2024-01-11 11:08:08', '/api/admin/system/admin/list.get', 83);
INSERT INTO `admin_user_role_access` VALUES (85, '创建', '空', 'SYSTEM_EMPLOYEE_CREATE', '2024-01-10 18:30:29', '2024-01-11 11:08:22', '/api/admin/system/admin.post', 83);
INSERT INTO `admin_user_role_access` VALUES (86, '更新', '空', 'SYSTEM_EMPLOYEE_UPDATE', '2024-01-10 18:30:47', '2024-01-11 11:08:27', '/api/admin/system/admin.put', 83);
INSERT INTO `admin_user_role_access` VALUES (87, '默认权限', '空', NULL, '2024-01-11 18:40:42', '2024-01-11 10:40:42', NULL, 0);
INSERT INTO `admin_user_role_access` VALUES (88, '当前登录信息', '空', NULL, '2024-01-11 18:41:00', '2024-01-11 10:41:00', '/api/admin/account', 87);
INSERT INTO `admin_user_role_access` VALUES (89, '系统日志', '空', NULL, '2024-01-11 19:55:01', '2024-01-11 11:55:01', NULL, 1);
INSERT INTO `admin_user_role_access` VALUES (90, '查询', NULL, 'SYSTEM_LOG_VIEW', '2024-01-11 19:55:42', '2024-01-11 11:55:42', '/api/admin/system/logs.get', 89);
INSERT INTO `admin_user_role_access` VALUES (91, '系统配置', '空', NULL, '2024-01-11 19:56:43', '2024-01-11 11:56:43', NULL, 1);
INSERT INTO `admin_user_role_access` VALUES (92, '查询', '空', 'SYSTEM_CONFIG_VIEW', '2024-01-11 19:57:12', '2024-01-11 11:57:12', '/api/admin/system/config/list.get', 91);
INSERT INTO `admin_user_role_access` VALUES (93, '创建', '空', 'SYSTEM_CONFIG_CREATE', '2024-01-11 19:57:50', '2024-01-11 11:57:50', '/api/admin/system/config.post', 91);
INSERT INTO `admin_user_role_access` VALUES (95, '更新', '空', 'SYSTEM_CONFIG_UPDATE', '2024-01-11 19:58:43', '2024-01-11 11:58:43', '/api/admin/system/config.put', 91);
INSERT INTO `admin_user_role_access` VALUES (100, '删除', '空', 'SYSTEM_CONFIG_DELETE', '2024-01-11 20:01:49', '2024-01-11 12:01:49', '/api/admin/system/config.delete', 91);
INSERT INTO `admin_user_role_access` VALUES (101, '网站设置', '空', 'SITE', '2024-01-13 18:42:41', '2024-01-13 10:42:58', NULL, 0);
INSERT INTO `admin_user_role_access` VALUES (102, '菜单管理', '空', NULL, '2024-01-13 18:42:49', '2024-01-13 10:42:49', NULL, 101);
INSERT INTO `admin_user_role_access` VALUES (103, '查询', '空', 'SITE_MENU_VIEW', '2024-01-13 18:43:36', '2024-01-13 10:43:36', '/api/admin/menu/list.get', 102);
INSERT INTO `admin_user_role_access` VALUES (104, '创建', '空', 'SITE_MENU_CREATE', '2024-01-13 18:44:03', '2024-01-13 10:44:03', '/api/admin/menu.post', 102);
INSERT INTO `admin_user_role_access` VALUES (106, '更新', '空', 'SITE_MENU_UPDATE', '2024-01-13 18:51:37', '2024-01-13 10:51:37', '/api/admin/menu.post', 102);
INSERT INTO `admin_user_role_access` VALUES (107, '删除', NULL, 'SITE_MENU_DELETE', '2024-01-13 18:52:20', '2024-01-13 10:52:20', 'api/admin/menu.delete', 102);
INSERT INTO `admin_user_role_access` VALUES (108, '修改信息', '空', NULL, '2024-01-17 15:53:58', '2024-01-17 07:53:58', '/api/admin/account.put', 87);
INSERT INTO `admin_user_role_access` VALUES (109, '修改密码', '空', NULL, '2024-01-17 15:54:34', '2024-01-17 07:56:35', '/api/admin/account/password.put', 87);
INSERT INTO `admin_user_role_access` VALUES (110, 'tag树', '空', 'Tag_tree', '2024-01-17 22:07:29', '2024-01-17 14:07:54', '/api/admin/tag/tree.get', 19);
INSERT INTO `admin_user_role_access` VALUES (111, '标签排序', '空', 'tag_sort', '2024-01-17 22:13:22', '2024-02-27 15:51:05', '/api/admin/tag/sort.post', 19);
INSERT INTO `admin_user_role_access` VALUES (112, '单个工具查询', '单个', 'TOOL_get', '2024-01-17 22:14:58', '2024-01-17 14:14:58', '/api/admin/tool.get', 30);
INSERT INTO `admin_user_role_access` VALUES (113, '栏目排序', '空', 'POST_COLUMN_SORT', '2024-01-17 22:16:54', '2024-01-17 14:16:54', '/api/admin/column/sort.get', 46);
INSERT INTO `admin_user_role_access` VALUES (114, '单个文章查询', '空', 'POST_GET', '2024-01-17 22:17:35', '2024-01-17 14:17:35', '/api/admin/post.get', 51);
INSERT INTO `admin_user_role_access` VALUES (115, '用户记录', NULL, 'USER_HISTORY', '2024-01-20 14:51:26', '2024-01-20 06:51:26', NULL, 8);
INSERT INTO `admin_user_role_access` VALUES (116, '浏览记录', '空', 'USER_HISTORY_VIEW', '2024-01-20 14:52:05', '2024-01-21 07:07:10', '/api/admin/user/history/view.get', 115);
INSERT INTO `admin_user_role_access` VALUES (117, '登录记录', '空', 'USER_HISTORY_LOGIN', '2024-01-20 14:52:57', '2024-01-21 07:07:20', '/api/admin/user/history/login.get', 115);
INSERT INTO `admin_user_role_access` VALUES (118, '用户评论', '空', 'USER_COMMENT', '2024-01-21 15:05:25', '2024-01-21 07:05:25', NULL, 8);
INSERT INTO `admin_user_role_access` VALUES (119, '查询', '空', 'USER_COMMENT_VIEW', '2024-01-21 15:06:16', '2024-01-21 07:06:15', '/api/admin/user/comment/list.get', 118);
INSERT INTO `admin_user_role_access` VALUES (120, '邮件记录', '空', 'USER_HISTORY_LIKE', '2024-01-21 19:27:37', '2024-05-11 13:40:34', '/api/admin/user/history/email.get', 115);
INSERT INTO `admin_user_role_access` VALUES (121, '短信记录', '空', 'USER_HISTORY_SMS', '2024-01-21 19:27:57', '2024-01-21 11:27:57', '/api/admin/user/history/sms.get', 115);
INSERT INTO `admin_user_role_access` VALUES (122, '自定义页面', '空', NULL, '2024-01-21 20:00:24', '2024-01-21 12:00:24', NULL, 101);
INSERT INTO `admin_user_role_access` VALUES (123, '查询', NULL, 'SITE_PAGE_VIEW', '2024-01-21 20:01:24', '2024-01-21 12:01:41', '/api/admin/page/list.get', 122);
INSERT INTO `admin_user_role_access` VALUES (124, '更新', '空', 'SITE_PAGE_UPDATE', '2024-01-21 20:02:25', '2024-01-21 12:03:03', '/api/admin/page.put', 122);
INSERT INTO `admin_user_role_access` VALUES (125, '创建', '空', 'SITE_PAGE_CREATE', '2024-01-21 20:02:56', '2024-01-21 12:02:56', '/api/admin/page.post', 122);
INSERT INTO `admin_user_role_access` VALUES (126, '删除', '空', 'SITE_PAGE_DELETE', '2024-01-21 20:03:19', '2024-01-21 12:03:19', '/api/admin/page.delete', 122);
INSERT INTO `admin_user_role_access` VALUES (127, '迭代管理', '空', NULL, '2024-02-03 16:27:26', '2024-02-03 08:27:26', NULL, 101);
INSERT INTO `admin_user_role_access` VALUES (128, '查询', '空', 'SITE_ITERATION_VIEW', '2024-02-03 16:27:53', '2024-02-03 08:28:13', '/api/admin/iterations/list.get', 127);
INSERT INTO `admin_user_role_access` VALUES (129, '创建', '空', 'SITE_ITERATION_CREATE', '2024-02-03 16:28:30', '2024-02-03 08:28:46', '/api/admin/iterations.post', 127);
INSERT INTO `admin_user_role_access` VALUES (130, '更新', '空', 'SITE_ITERATION_UPDATE', '2024-02-03 16:29:13', '2024-02-03 08:29:13', '/api/admin/iterations.put', 127);
INSERT INTO `admin_user_role_access` VALUES (131, '工具反馈', '空', NULL, '2024-02-04 14:45:49', '2024-02-04 06:45:49', NULL, 18);
INSERT INTO `admin_user_role_access` VALUES (132, '查询', '空', 'TOOL_FEEDBACK_VIEW', '2024-02-04 14:46:07', '2024-02-04 06:46:37', '/api/admin/tool/feedback/list.get', 131);
INSERT INTO `admin_user_role_access` VALUES (133, '修改', '空', 'TOOL_FEEDBACK_UPDATE', '2024-02-04 14:46:57', '2024-02-04 15:22:57', '/api/admin/tool/feedback.post', 131);
INSERT INTO `admin_user_role_access` VALUES (134, '用户提交', '空', NULL, '2024-02-04 14:47:25', '2024-02-04 06:47:25', NULL, 18);
INSERT INTO `admin_user_role_access` VALUES (135, '查询', '空', 'TOOL_COLLECT_VIEW', '2024-02-04 14:47:42', '2024-02-04 17:04:31', '/api/admin/tool/collect/list.get', 134);
INSERT INTO `admin_user_role_access` VALUES (136, '更新', '空', 'TOOL_COLLECT_UPDATE', '2024-02-04 14:48:16', '2024-02-04 17:04:42', '/api/admin/tool/collect.post', 134);
INSERT INTO `admin_user_role_access` VALUES (137, '监控面板', '空', NULL, '2024-02-04 15:28:30', '2024-02-04 15:28:30', NULL, 0);
INSERT INTO `admin_user_role_access` VALUES (138, '注册统计', NULL, NULL, '2024-02-04 15:29:58', '2024-02-04 15:29:58', '/api/admin/analysis/register.get', 137);
INSERT INTO `admin_user_role_access` VALUES (139, '访问量统计', '空', NULL, '2024-02-04 15:30:40', '2024-02-04 15:30:40', '/api/admin/analysis/visit.get', 137);
INSERT INTO `admin_user_role_access` VALUES (140, '登陆量统计', '空', NULL, '2024-02-04 15:31:16', '2024-02-04 15:31:16', '/api/admin/analysis/login.get', 137);
INSERT INTO `admin_user_role_access` VALUES (141, '点赞量统计', '空', NULL, '2024-02-04 15:32:01', '2024-02-04 15:32:01', '/api/admin/analysis/like.get', 137);
INSERT INTO `admin_user_role_access` VALUES (142, '时间筛选访问量', '空', NULL, '2024-02-04 15:32:55', '2024-02-04 15:32:55', '/api/admin/analysis/visit_by_time.get', 137);
INSERT INTO `admin_user_role_access` VALUES (143, '搜索排行', '空', NULL, '2024-02-04 15:33:28', '2024-02-04 15:33:28', '/api/admin/rank/search.get', 137);
INSERT INTO `admin_user_role_access` VALUES (144, '设备统计', '空', NULL, '2024-02-04 15:33:54', '2024-02-04 15:33:54', '/api/admin/analysis/device.get', 137);
INSERT INTO `admin_user_role_access` VALUES (145, '位置统计', '空', NULL, '2024-02-04 15:34:23', '2024-02-04 15:34:23', '/api/admin/analysis/position.get', 137);
INSERT INTO `admin_user_role_access` VALUES (146, '工具爬虫', '空', 'TOOL_CRAWLER', '2024-02-06 16:20:53', '2024-02-06 16:20:53', NULL, 18);
INSERT INTO `admin_user_role_access` VALUES (147, '标签转移', '空', 'tools_move', '2024-02-27 15:40:35', '2024-02-27 15:40:35', '/api/admin/tag/tools_move.post', 19);
INSERT INTO `admin_user_role_access` VALUES (148, '对话权限', '空', NULL, '2024-05-07 21:44:51', '2024-05-07 21:44:51', NULL, 0);
INSERT INTO `admin_user_role_access` VALUES (149, '助手', '空', NULL, '2024-05-07 21:46:03', '2024-05-07 21:46:03', NULL, 148);
INSERT INTO `admin_user_role_access` VALUES (150, '订单', '空', NULL, '2024-05-07 21:46:19', '2024-05-07 21:46:19', NULL, 148);
INSERT INTO `admin_user_role_access` VALUES (151, '平台', '空', NULL, '2024-05-07 21:46:26', '2024-05-07 21:46:26', NULL, 148);
INSERT INTO `admin_user_role_access` VALUES (152, '查询', '空', 'CHAT_ASSISTANT_VIEW', '2024-05-07 21:46:40', '2024-05-09 23:58:31', '/api/admin/chat_role/list.get', 149);
INSERT INTO `admin_user_role_access` VALUES (153, '查询', '空', 'CHAT_ORDER_VIEW', '2024-05-07 21:46:49', '2024-05-09 23:59:30', '/api/admin/order/list.get', 150);
INSERT INTO `admin_user_role_access` VALUES (154, '查询', '空', 'CHAT_MODEL_VIEW', '2024-05-07 21:47:00', '2024-05-09 23:59:49', '/api/admin/chat_api_key/list.get', 151);
INSERT INTO `admin_user_role_access` VALUES (155, 'role_tag', '空', NULL, '2024-05-10 00:01:24', '2024-05-10 00:01:24', '/api/admin/chat_role_tag/list.get', 149);
INSERT INTO `admin_user_role_access` VALUES (156, 'chat_model', '空', NULL, '2024-05-10 00:01:59', '2024-05-10 00:01:59', '/api/admin/chat_models/list.get', 151);
INSERT INTO `admin_user_role_access` VALUES (157, '修改', '空', NULL, '2024-05-10 17:51:12', '2024-05-10 17:51:12', '/api/admin/chat_api_key.put', 151);
INSERT INTO `admin_user_role_access` VALUES (158, 'chat model 增加', '空', NULL, '2024-05-10 17:53:23', '2024-05-10 17:53:23', '/api/admin/chat_models.post', 151);
INSERT INTO `admin_user_role_access` VALUES (159, 'chat model 更新', '空', NULL, '2024-05-10 17:56:22', '2024-05-10 17:58:45', '/api/admin/chat_models.put', 151);
INSERT INTO `admin_user_role_access` VALUES (160, '获取配置', '空', NULL, '2024-05-10 18:42:40', '2024-05-10 18:42:40', '/api/admin/system/config.get', 91);
INSERT INTO `admin_user_role_access` VALUES (161, '套餐', '空', NULL, '2024-05-11 13:42:46', '2024-05-11 13:42:46', NULL, 148);
INSERT INTO `admin_user_role_access` VALUES (162, '查询', '空', 'CHAT_PLAN_VIEW', '2024-05-11 13:42:55', '2024-05-11 13:44:14', '/api/admin/shop/list.get', 161);
INSERT INTO `admin_user_role_access` VALUES (163, '修改', '空', NULL, '2024-05-11 13:46:39', '2024-05-11 13:46:39', '/api/admin/shop.put', 161);
INSERT INTO `admin_user_role_access` VALUES (164, '增加', '空', NULL, '2024-05-11 13:46:54', '2024-05-11 13:46:54', '/api/admin/shop.post', 161);
INSERT INTO `admin_user_role_access` VALUES (165, '创建', '空', NULL, '2024-05-11 14:06:14', '2024-05-11 14:06:14', '/api/admin/chat_role.post', 149);
INSERT INTO `admin_user_role_access` VALUES (166, '更新', '空', NULL, '2024-05-11 14:06:33', '2024-05-11 14:06:33', '/api/admin/chat_role.put', 149);
INSERT INTO `admin_user_role_access` VALUES (167, '删除', '空', NULL, '2024-05-11 14:51:59', '2024-05-11 14:51:59', '/api/admin/chat_role.delete', 149);
INSERT INTO `admin_user_role_access` VALUES (168, 'chat_model sort', '空', NULL, '2024-05-11 17:38:30', '2024-05-11 17:40:03', '/api/admin/chat_models/sort.post', 151);
INSERT INTO `admin_user_role_access` VALUES (169, '增加token', '空', NULL, '2024-05-12 16:43:16', '2024-05-12 16:43:16', '/api/admin/user/add_token.post', 9);

-- ----------------------------
-- Table structure for admin_user_token
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_token`;
CREATE TABLE `admin_user_token`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_id` int(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration_time` int(0) NULL DEFAULT NULL,
  `state` smallint(0) NULL DEFAULT 0 COMMENT '1有效 2无效',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `token`(`token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 154 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_api_keys
-- ----------------------------
DROP TABLE IF EXISTS `chat_api_keys`;
CREATE TABLE `chat_api_keys`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `platform` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '平台  按接口格式区分',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '名称',
  `value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'API KEY value',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chat' COMMENT '用途（chat=>聊天，img=>图片）',
  `desc` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '描述',
  `api_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT 'API 地址',
  `enabled` tinyint(1) NULL DEFAULT NULL COMMENT '是否启用',
  `use_proxy` tinyint(1) NULL DEFAULT NULL COMMENT '是否使用代理访问',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `sort_num` smallint(0) NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'OpenAI API ' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_api_keys
-- ----------------------------
INSERT INTO `chat_api_keys` VALUES ('195a05e4-60ff-4e9b-9cfa-1266d68e70e1', 'OpenAI', '月之暗面', 'sk-lQr3Dxxx', 'chat', '月之暗面', 'https://api.moonshot.cn/v1', 1, 0, '2024-03-21 20:33:05', '2024-05-07 21:48:04', 'https://api.chat.aieo.cn/upload/imgs/20240325/161720_1711354640.png', 1);
INSERT INTO `chat_api_keys` VALUES ('4c06922a-efbd-4075-9947-7f7407a4c124', 'OpenAI', 'ChatGPT', 'sk-proxxxxxqZ3ReNvFJtBxCaT3BlbkFJc1po39QBdjQFCwuPV9UF', 'chat', 'OpenAI旗下', 'https://gateway.ai.cloudflare.com/v1/d3fdd41dcfed0ced9ccc57216544be8c/aichat/openai', 1, 0, '2024-02-28 12:03:11', '2024-05-10 17:51:51', 'https://api.chat.aieo.cn/upload/imgs/20240317/103720_1710671840.png', 3);
INSERT INTO `chat_api_keys` VALUES ('95e9cea9-1093-45a2-9e2e-2512ad879c10', 'QWen', '通义千问', 'sk-058ef8a7fda949a88c5cxxxx3ab4b', 'chat', '阿里旗下的AI对话助手', 'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation', 0, 0, '2024-02-27 20:55:27', '2024-05-07 21:55:36', 'https://api.chat.aieo.cn/upload/imgs/20240317/104713_1710672433.png', 2);
INSERT INTO `chat_api_keys` VALUES ('a50b66e6-7776-4a3a-99f3-ef1964d65754', 'XunFei', '讯飞星火', '2ea9f838|3649ebb29d608a9b6b1f636575983cc5|YWEzODZjMTdhMTRkNDIyNjNxxxxZWRj', 'chat', '科大讯飞旗下', 'ws://spark-api.xf-yun.com/v3.1/chat', 0, 0, '2024-02-27 20:56:05', '2024-05-07 21:55:39', 'https://api.chat.aieo.cn/upload/imgs/20240317/103835_1710671915.png', 4);

-- ----------------------------
-- Table structure for chat_client_id_log
-- ----------------------------
DROP TABLE IF EXISTS `chat_client_id_log`;
CREATE TABLE `chat_client_id_log`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `client_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `expiration_time` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_history
-- ----------------------------
DROP TABLE IF EXISTS `chat_history`;
CREATE TABLE `chat_history`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户 ID',
  `chat_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '会话 ID',
  `type` enum('prompt','reply','delete') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '类型：prompt|reply|delete',
  `role_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色 ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '聊天内容',
  `tokens` int(0) NOT NULL COMMENT '耗费 token 数量',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '聊天历史记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_items
-- ----------------------------
DROP TABLE IF EXISTS `chat_items`;
CREATE TABLE `chat_items`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '用户 ID',
  `role_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '角色 ID',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '会话标题',
  `model_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0' COMMENT '模型 ID',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `state` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `use_context` tinyint(1) NULL DEFAULT 0 COMMENT '0不使用联系上下文 1使用',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户会话列表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_models
-- ----------------------------
DROP TABLE IF EXISTS `chat_models`;
CREATE TABLE `chat_models`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `key_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '模型的对应的key id',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '模型名称',
  `value` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '模型值',
  `sort_num` tinyint(1) NOT NULL COMMENT '排序数字',
  `enabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否启用模型',
  `magnification` decimal(6, 1) NOT NULL COMMENT '倍率',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `vision` tinyint(1) NULL DEFAULT 0 COMMENT '是否支持图片识别',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = 'AI 模型表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_models
-- ----------------------------
INSERT INTO `chat_models` VALUES ('007f9750-5ec3-49e0-8fb3-0377287d09c6', '4c06922a-efbd-4075-9947-7f7407a4c124', 'GPT-4 Turbo', 'gpt-4-turbo-2024-04-09', 4, 1, 10.0, '2024-05-11 14:09:37', '2024-05-11 17:36:07', 0);
INSERT INTO `chat_models` VALUES ('319baa59-d189-499d-ac88-81fee89a6709', '4c06922a-efbd-4075-9947-7f7407a4c124', 'GPT-4', 'gpt-4-0613', 1, 0, 1.0, '2024-05-10 17:53:39', '2024-05-11 14:07:37', 0);
INSERT INTO `chat_models` VALUES ('38b47e87-9176-499d-97d9-1a9490a1c5e1', '95e9cea9-1093-45a2-9e2e-2512ad879c10', '通义千问-Max', 'qwen-max-1201', 2, 1, 3.0, '2024-03-25 12:06:56', '2024-03-25 21:49:43', 0);
INSERT INTO `chat_models` VALUES ('3ab93d6f-9d6b-4431-924a-99a9c9da71d2', '95e9cea9-1093-45a2-9e2e-2512ad879c10', '通义千问-Turbo', 'qwen-turbo', 3, 1, 1.0, '2024-03-25 12:01:59', '2024-03-25 18:42:36', 0);
INSERT INTO `chat_models` VALUES ('511f3fa9-a789-41b7-82c7-b7f511831854', '195a05e4-60ff-4e9b-9cfa-1266d68e70e1', 'moonshot-v1-128k', 'moonshot-v1-128k', 3, 1, 5.0, '2024-03-25 22:20:17', '2024-05-11 17:23:59', 0);
INSERT INTO `chat_models` VALUES ('526c1541-27fb-4664-8625-762ae307bf99', '4c06922a-efbd-4075-9947-7f7407a4c124', 'GPT-3.5 Turbo', 'gpt-3.5-turbo-1106', 3, 1, 1.5, '2024-03-25 12:08:12', '2024-05-11 17:34:37', 0);
INSERT INTO `chat_models` VALUES ('8fda8f0a-e998-4839-9078-3a3f466ca4c8', '4c06922a-efbd-4075-9947-7f7407a4c124', 'gpt-4-1106', 'gpt-4-1106-vision-preview', 2, 0, 1.0, '2024-05-10 18:09:17', '2024-05-11 14:10:09', 0);
INSERT INTO `chat_models` VALUES ('92fa5fd3-639c-48b0-8a24-ac4b87c13d3f', '95e9cea9-1093-45a2-9e2e-2512ad879c10', '通义千问-Plus', 'qwen-plus', 1, 1, 1.5, '2024-03-25 12:06:42', '2024-03-25 21:49:48', 0);
INSERT INTO `chat_models` VALUES ('d143d495-6cae-4c24-a6a7-260d180beab5', '195a05e4-60ff-4e9b-9cfa-1266d68e70e1', 'moonshot-v1-8k', 'moonshot-v1-8k', 1, 1, 1.0, '2024-03-25 22:18:12', '2024-03-25 22:18:12', 0);
INSERT INTO `chat_models` VALUES ('d26e4ef0-de62-4f0d-a615-8c8e0348579f', '195a05e4-60ff-4e9b-9cfa-1266d68e70e1', 'moonshot-v1-32k', 'moonshot-v1-32k', 2, 1, 2.0, '2024-03-25 22:20:05', '2024-03-25 22:23:12', 0);

-- ----------------------------
-- Table structure for chat_role_tag
-- ----------------------------
DROP TABLE IF EXISTS `chat_role_tag`;
CREATE TABLE `chat_role_tag`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `sort_num` int(0) NULL DEFAULT 0,
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_role_tag
-- ----------------------------
INSERT INTO `chat_role_tag` VALUES ('403c7bb6-af42-440d-8057-17249c7d9658', '写作', '2024-03-31 12:22:24', '2024-04-07 14:46:27', 4, 'IconArticle');
INSERT INTO `chat_role_tag` VALUES ('46789460-6fbc-4a1c-b447-d0e20472649d', '生产力', '2024-03-31 12:22:32', '2024-04-07 14:50:51', 5, 'IconBulb');
INSERT INTO `chat_role_tag` VALUES ('620155fd-87e5-4a02-9cf6-dc19827aa1e1', '聊天', '2024-03-31 12:22:18', '2024-04-07 14:46:09', 2, 'IconEmoji');
INSERT INTO `chat_role_tag` VALUES ('8b804920-bc05-41a0-8bc8-6e1b7a8ed3e7', '娱乐', '2024-04-21 14:51:10', '2024-04-21 14:51:26', 1, 'IconCustomerSupport');
INSERT INTO `chat_role_tag` VALUES ('d4e95fec-f642-4b97-b63e-ae6b084b4b35', '编程', '2024-03-30 21:23:50', '2024-04-07 14:41:09', 3, 'IconTerminal');

-- ----------------------------
-- Table structure for chat_roles
-- ----------------------------
DROP TABLE IF EXISTS `chat_roles`;
CREATE TABLE `chat_roles`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '角色名称',
  `context` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '角色语料 json',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '打招呼信息',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '角色图标',
  `enabled` tinyint(1) NULL DEFAULT 1 COMMENT '是否发布',
  `sort_num` smallint(0) NULL DEFAULT 0 COMMENT '角色排序',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `tag_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `hello_msg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `suggestions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'system' COMMENT '创建人',
  `is_delete` tinyint(1) NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '聊天角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_roles
-- ----------------------------
INSERT INTO `chat_roles` VALUES ('06b55551-0296-4f9a-a60c-9f5da6cc2943', '抖音脚本撰写器', '您是一款旨在协助撰写抖音引人入胜短视频脚本的人工智能。您的主要功能是根据用户提供的主题、目标受众和任何特定元素，生成创意、引人入胜且适合平台的脚本。您可以建议流行话题、融入相关标签，并就观众参与和内容病毒传播的最佳实践提供建议。\n\n工作流程：\n1、初始简报：从用户那里收集有关他们视频创意的信息，包括主题、目标受众、期望语气和任何他们希望包含的特定元素（例如，流行音频、挑战）。\n2、内容研究：根据初始简报，进行研究以确定当前TikTok趋势、热门标签和与用户主题相符的相关挑战。\n3、草拟脚本：生成一个草案脚本，融入用户的要求、流行元素和观众参与和内容病毒传播的最佳实践。\n4、用户反馈：向用户呈现草案脚本以征求反馈。进行必要的修改，以确保脚本符合用户期望并针对TikTok进行了优化。\n5、最终确定：向用户交付最终脚本，包括任何额外的建议，以增强视频的病毒传播潜力。\n\n约束：\n脚本必须简洁，最好在60秒或更短，以适应抖音的格式。\n必须融入至少一个与主题相关的流行标签或挑战。\n开场必须在前3秒内吸引注意力。\n内容应适应抖音固有的垂直视频格式', '抖音脚本撰写器', 'https://api.chat.aieo.cn/upload/imgs/20240512/153121_1715499081.jpg', 1, 0, '2024-05-12 15:31:23', '2024-05-12 15:31:23', '46789460-6fbc-4a1c-b447-d0e20472649d', NULL, NULL, 'system', 0);
INSERT INTO `chat_roles` VALUES ('647fc513-1cb8-48f9-af2a-662a53b8bbe4', '修仙模拟器', '你是一个修仙模拟器游戏，你每次回复都会触发随机事件并且给我几个选项，每次事件选择我通过之后都有一定几率会长经验，并且会有一定的风险回受伤，受伤会减少生命值，一共100生命值，减少到0时游戏结束角色死亡。 每100经验晋升一个境界，境界需要根据仙逆小说里面的境界来计算，当前状态的生命值和境界相关数据在每次回复的顶部输出, 形式: 生命值: `100` ，经验: `80` ，境界: `筑基期`', '开始你的修仙之路', 'https://api.chat.aieo.cn/upload/imgs/20240512/142029_1715494829.jpg', 1, 0, '2024-05-12 14:20:31', '2024-05-12 15:14:38', '8b804920-bc05-41a0-8bc8-6e1b7a8ed3e7', NULL, '[\"我要修仙\",\"我要成魔\"]', 'system', 0);
INSERT INTO `chat_roles` VALUES ('75dc28c2-d780-41e2-9e92-f4492ab0b079', '前端面试官', '你是一个前端面试官，以一问一答的形式，当我回答完一个问题，再问我下一个，问题需要包含JavaScript、css、前端框架相关的深层次问题，根据我的回答给我打分，并给出最终的结果并指出我回答过程的错误', '由我来充当你的前端面试官，我会评估和指出你面试中的错误', 'https://api.chat.aieo.cn/upload/imgs/20240512/135350_1715493230.jpg', 1, 0, '2024-05-12 13:53:52', '2024-05-12 14:10:38', 'd4e95fec-f642-4b97-b63e-ae6b084b4b35', '你需要面试的等级', '[\"初级前端工程师\",\"高级前端工程师\"]', 'system', 0);
INSERT INTO `chat_roles` VALUES ('b5248b7e-9f5d-479c-a381-b184207387a6', '李白', '你是著名诗人李白，我会输入诗词主题，你要更具主题创作出合适的完整诗词，需要带标题，输出时只给出标题内容和诗词内容，不需要其他多余的信息。', '为你创建李白风格的诗词', 'https://api.chat.aieo.cn/upload/imgs/20240512/134020_1715492420.jpg', 1, 0, '2024-05-12 13:41:25', '2024-05-12 13:45:17', '403c7bb6-af42-440d-8057-17249c7d9658', '请输入需要生成的生词主题', '[]', 'system', 0);
INSERT INTO `chat_roles` VALUES ('system', '官方助手', '', '您好，我是您的AI智能助手，我会尽力回答您的问题或提供有用的建议。', 'https://api.chat.aieo.cn/upload/imgs/20240421/132300_1713676980.png', 1, 0, '2021-05-30 07:02:06', '2024-05-11 14:06:59', '620155fd-87e5-4a02-9cf6-dc19827aa1e1', NULL, '[]', 'system', 0);

-- ----------------------------
-- Table structure for home_email
-- ----------------------------
DROP TABLE IF EXISTS `home_email`;
CREATE TABLE `home_email`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `scene` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `expiration_time` datetime(0) NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_login_log
-- ----------------------------
DROP TABLE IF EXISTS `home_login_log`;
CREATE TABLE `home_login_log`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_orders
-- ----------------------------
DROP TABLE IF EXISTS `home_orders`;
CREATE TABLE `home_orders`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `total_amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额',
  `status` enum('Success','Pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '支付状态',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT '修改时间',
  `shop_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `token` int(0) NULL DEFAULT NULL,
  `pay_type` enum('Wechat','Alipay') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_shops
-- ----------------------------
DROP TABLE IF EXISTS `home_shops`;
CREATE TABLE `home_shops`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tokens` int(0) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '价格',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `enable` tinyint(1) NULL DEFAULT 0 COMMENT '状态 上架1  已下价0',
  `origin_price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of home_shops
-- ----------------------------
INSERT INTO `home_shops` VALUES ('1267790f-78e2-4d1c-ba4d-84b0dab5654b', '青铜套餐', NULL, 1000000, 19.90, NULL, 1, 23.45, '2024-05-11 17:14:28', '2024-05-11 17:14:33');
INSERT INTO `home_shops` VALUES ('27eb116c-1991-45eb-8f06-6eca7a5748d5', '铂金套餐', NULL, 2000000, 21.20, NULL, 1, 48.88, '2024-05-11 17:17:13', '2024-05-11 17:17:15');
INSERT INTO `home_shops` VALUES ('b6e1c1c8-dd5a-46dc-94df-3d41b1419a8c', '体验', NULL, 500000, 6.00, NULL, 1, 13.00, '2024-03-25 15:02:52', '2024-05-11 17:15:00');
INSERT INTO `home_shops` VALUES ('c504587c-f651-4c97-98e2-2740edf5729f', '白银套餐', NULL, 1500000, 16.90, NULL, 1, 39.99, '2024-05-11 17:16:06', '2024-05-11 17:16:08');

-- ----------------------------
-- Table structure for home_sms
-- ----------------------------
DROP TABLE IF EXISTS `home_sms`;
CREATE TABLE `home_sms`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `scene` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '\"login\" | \"register\" | \"reset\" | \"bind\"',
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `expiration_time` datetime(0) NULL DEFAULT NULL COMMENT '到期时间',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_user
-- ----------------------------
DROP TABLE IF EXISTS `home_user`;
CREATE TABLE `home_user`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '手机号/作为登陆账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `role_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0' COMMENT '角色用户表',
  `class` int(0) NULL DEFAULT 1 COMMENT '等级',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `login_count` int(0) NULL DEFAULT 0 COMMENT '总登录次数',
  `last_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '最后登陆ip',
  `comment_count` int(0) NULL DEFAULT 0,
  `first_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `state` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '正常',
  `tokens` int(0) NULL DEFAULT 0 COMMENT 'token量',
  `all_tokens` int(0) NULL DEFAULT 0 COMMENT 'token累计总量',
  `wechat_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE,
  UNIQUE INDEX `phone`(`phone`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of home_user
-- ----------------------------
INSERT INTO `home_user` VALUES ('system', NULL, NULL, '2024-04-21 13:26:01', NULL, '0', 1, '系统', '/icon.png', 0, NULL, 0, NULL, NULL, NULL, '正常', 3050, 3050, NULL);

-- ----------------------------
-- Table structure for home_user_like_role
-- ----------------------------
DROP TABLE IF EXISTS `home_user_like_role`;
CREATE TABLE `home_user_like_role`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `delete_at` datetime(0) NULL DEFAULT NULL,
  `sort_num` smallint(0) NULL DEFAULT NULL,
  `state` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_user_token
-- ----------------------------
DROP TABLE IF EXISTS `home_user_token`;
CREATE TABLE `home_user_token`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration_time` datetime(0) NULL DEFAULT NULL,
  `state` smallint(0) NULL DEFAULT 1 COMMENT '1有效 2无效',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE,
  UNIQUE INDEX `token`(`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_user_token_records
-- ----------------------------
DROP TABLE IF EXISTS `home_user_token_records`;
CREATE TABLE `home_user_token_records`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chat_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `amount` int(0) NULL DEFAULT NULL COMMENT '数量',
  `type` enum('add','reducing') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '类别 减少  增加',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `desc` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '套餐增加  签到增加 活动赠送 对话消费',
  `balance` int(0) NULL DEFAULT NULL COMMENT '剩余',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_user_view_history
-- ----------------------------
DROP TABLE IF EXISTS `home_user_view_history`;
CREATE TABLE `home_user_view_history`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '查看的页面',
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `time` datetime(0) NULL DEFAULT NULL COMMENT '访问的时间',
  `source_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `source_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '访问ip',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for home_user_view_logs
-- ----------------------------
DROP TABLE IF EXISTS `home_user_view_logs`;
CREATE TABLE `home_user_view_logs`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '访问页面',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `device` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '设备',
  `os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '操作系统',
  `browser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '浏览器',
  `browser_vesion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '浏览器版本',
  `robot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '是否是机器人  不是则为机器人的类别',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uuid`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for iterations
-- ----------------------------
DROP TABLE IF EXISTS `iterations`;
CREATE TABLE `iterations`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `version` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '版本号',
  `date` datetime(0) NULL DEFAULT NULL COMMENT '发布时间',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '发布的内容',
  `modules` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '迭代的模块',
  `persons` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '迭代人/团队',
  `status` enum('Planning','Released','Obsolete') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '状态',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for operator_log
-- ----------------------------
DROP TABLE IF EXISTS `operator_log`;
CREATE TABLE `operator_log`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `user_id` int(0) NULL DEFAULT 1 COMMENT '操作人id  默认则为系统',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '操作了网站什么',
  `created_at` timestamp(0) NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作的时间',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `request_data` json NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7437 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for page
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page`  (
  `id` int(0) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `time` datetime(0) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of page
-- ----------------------------
INSERT INTO `page` VALUES (1, 'about', '关于我们页面', '<h1 style=\"text-align: start;\"><strong>AIEO-AI探索者</strong></h1><h2 style=\"text-align: start;\">关于我们 </h2><p style=\"text-align: start;\">在这个AI技术日新月异的时代，我们见证了人工智能工具和服务的蓬勃发展。然而，在这片繁花似锦的科技花园中，用户往往面临选择困难：哪些工具真正适合自己的需求？如何在众多选项中做出最佳选择？正是基于这样的背景，<strong>AI探索者-AIEO（AI Explorer Online）</strong>应运而生。</p><h2 style=\"text-align: start; line-height: 1.5;\">我们的使命</h2><p style=\"text-align: start;\">作为一个在线人工智能探索平台，AI探索者-AIEO致力于成为用户发现和选择合适AI工具和服务的灯塔。我们深知，寻找合适的工具既费时又费力，尤其是在信息爆炸的今天。我们的平台旨在为AI爱好者和使用者提供一个清晰、直观、高效的导航空间，让每一位用户都能在这浩瀚如海的AI工具中找到那抹为自己量身定制的光亮。</p><h2 style=\"text-align: start; line-height: 1.5;\">我们的优势</h2><p style=\"text-align: start;\">区别于市场上其他AI导航工具，我们不仅仅是一个信息的聚合地。我们的团队不断地测试和评估各种AI工具和服务，以确保我们提供的推荐是基于实际性能和用户体验的。我们的平台集合了工具整理、标注、测评、监测、教程等多维度信息，确保用户能够一站式解决问题。</p><h2 style=\"text-align: start; line-height: 1.5;\">与众不同的交互体验</h2><p style=\"text-align: start;\">我们深知，优秀的用户体验是互联网产品的灵魂。因此，AI探索者-AIEO采用了先进的AI对话技术，用户无需繁琐地点击分类，只需告诉我们的AI机器人自己的需求，系统即可智能推荐最适合的工具，并详细介绍它们的区别。我们还提供了进一步的筛选选项，让用户能够根据自己的具体条件（如是否接受非中文界面、是否可付费等）快速定位到理想的工具。</p><h2 style=\"text-align: start; line-height: 1.5;\">开放和透明的更新历程</h2><p style=\"text-align: start;\">我们相信，每一步进步都值得被记录和分享。因此，我们承诺在“版本更新”页面公开每一次迭代更新的细节，包括新增功能、调整原因及其背后的思考。我们欢迎并鼓励所有用户提出建议，任何一经采纳的优化建议都将获得现金奖励，并在下一版本更新中公开致谢。</p><h2 style=\"text-align: start; line-height: 1.5;\">我们的期待</h2><p style=\"text-align: start;\">AI探索者-AIEO不仅仅是一个项目，它是我们对未来的憧憬，是我们对科技力量的信仰。我们期待通过不断的努力，为每一位走进AI探索者的朋友解决实际问题，同时也为AI改变世界的伟大进程贡献一份力量。让我们携手前行，在这个充满可能的AI时代，共同探索未来。</p><h2>广告投放</h2><p style=\"line-height: 2;\">若您对广告投放感兴趣的话，请查看<a href=\"https://ai-bot.cn/advertisement/\" target=\"\">广告合作</a>详情页。</p><h2>联系我们</h2><p style=\"line-height: 2;\">感谢你成为AI工具集大家庭的一员，我们可以一起参与和塑造人工智能的未来！</p><p style=\"line-height: 2;\">若你有任何意见和建议，无论是网站排版改进、AI工具提交，还是文章投稿、观点分享和商务合作，欢迎随时联系站长的邮箱<a href=\"mailto:info@chinag.pro\" target=\"_blank\">info@chinag.pro</a>进行交流和沟通。</p>', '2024-02-23 18:50:47', '关于我们');

SET FOREIGN_KEY_CHECKS = 1;
