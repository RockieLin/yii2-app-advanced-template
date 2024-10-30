-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-04-19 13:03:54
-- 服务器版本： 8.0.18
-- PHP 版本： 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 数据库： `yii2-advanced-template`
--
CREATE DATABASE IF NOT EXISTS `yii2-advanced-template` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `yii2-advanced-template`;

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
                         `id` int(10) NOT NULL COMMENT '編號',
                         `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '帳號',
                         `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密碼',
                         `name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名稱',
                         `status` int(1) NOT NULL DEFAULT '1' COMMENT '啟用狀態',
                         `role` int(1) NOT NULL DEFAULT '1' COMMENT '1: 主管理員 2: 副管理員',
                         `updated_at` int(10) NOT NULL,
                         `created_at` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理者帳號';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `name`, `status`, `role`, `updated_at`, `created_at`) VALUES
                                                                                                             (1, 'example@example.com', 'e10adc3949ba59abbe56e057f20f883e', '主帳號', 1, 1, 1457495630, 1300000000),
                                                                                                             (2, 'aa@aa.aa', 'e10adc3949ba59abbe56e057f20f883e', '副帳號1', 1, 2, 1458859085, 1458859085),
                                                                                                             (3, 'bb@bb.bb', 'e10adc3949ba59abbe56e057f20f883e', '副帳號2', 1, 3, 1458859108, 1458859108);

-- --------------------------------------------------------

--
-- 表的结构 `announce`
--

CREATE TABLE `announce` (
                            `id` int(10) NOT NULL COMMENT 'ID',
                            `status` int(1) NOT NULL DEFAULT '1' COMMENT '啟用狀態',
                            `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '標題',
                            `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '內文',
                            `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '圖片',
                            `keyword` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '關鍵字',
                            `start_time` int(10) NOT NULL COMMENT '開始時間',
                            `end_time` int(10) NOT NULL COMMENT '結束時間',
                            `updated_at` int(10) NOT NULL,
                            `created_at` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `announce`
--

INSERT INTO `announce` (`id`, `status`, `title`, `content`, `image`, `keyword`, `start_time`, `end_time`, `updated_at`, `created_at`) VALUES
    (1, 1, 'test', '<p>test</p><p>haha</p>', '/uploads/announce/5e9b3ae60d979.jpg', 'testtesthaha/uploads/announce/5e9b3ae60d979.jpg', 1586278800, 1588438800, 1587231462, 1587231462);

-- --------------------------------------------------------

--
-- 表的结构 `auth_assignment`
--

CREATE TABLE `auth_assignment` (
                                   `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                   `user_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                   `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
                                                                         ('1', '1', 1457495630),
                                                                         ('2', '2', 1458859085),
                                                                         ('3', '3', 1458859108);

-- --------------------------------------------------------

--
-- 表的结构 `auth_item`
--

CREATE TABLE `auth_item` (
                             `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                             `type` int(11) NOT NULL,
                             `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                             `rule_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                             `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                             `created_at` int(11) DEFAULT NULL,
                             `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
                                                                                                             ('1', 1, '主帳號', NULL, NULL, 1441195655, 1587231636),
                                                                                                             ('2', 1, '副帳號1', NULL, NULL, 1441195655, 1441195655),
                                                                                                             ('3', 1, '副帳號2', NULL, NULL, 1441195655, 1441195655),
                                                                                                             ('announce.*', 2, '資訊發佈', NULL, NULL, 1435339282, 1435339282),
                                                                                                             ('member.*', 2, '會員管理', NULL, NULL, 1435339282, 1435339282),
                                                                                                             ('permission.*', 2, '權限管理', NULL, NULL, 1435339282, 1435339282),
                                                                                                             ('user.*', 2, '後台帳號管理', NULL, NULL, 1435339282, 1435339282);

-- --------------------------------------------------------

--
-- 表的结构 `auth_item_child`
--

CREATE TABLE `auth_item_child` (
                                   `parent` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                   `child` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
                                                      ('1', 'announce.*'),
                                                      ('2', 'announce.*'),
                                                      ('1', 'member.*'),
                                                      ('2', 'member.*'),
                                                      ('3', 'member.*'),
                                                      ('1', 'permission.*'),
                                                      ('3', 'permission.*'),
                                                      ('1', 'user.*'),
                                                      ('2', 'user.*');

-- --------------------------------------------------------

--
-- 表的结构 `auth_rule`
--

CREATE TABLE `auth_rule` (
                             `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                             `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                             `created_at` int(11) DEFAULT NULL,
                             `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `email_check_codes`
--

CREATE TABLE `email_check_codes` (
                                     `id` int(10) NOT NULL,
                                     `member_id` int(10) NOT NULL,
                                     `check_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                     `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                                     `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'email',
                                     `other` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                     `created_at` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Email驗證欄碼';

-- --------------------------------------------------------

--
-- 表的结构 `member`
--

CREATE TABLE `member` (
                          `id` int(10) NOT NULL COMMENT 'ID',
                          `social_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'email' COMMENT '帳號類型',
                          `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '帳號',
                          `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密碼',
                          `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Email',
                          `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '真實姓名',
                          `status` int(1) NOT NULL COMMENT '0:停用 1:Email未認證 2:已認證',
                          `updated_at` int(10) DEFAULT NULL COMMENT '最後修改',
                          `created_at` int(100) DEFAULT NULL COMMENT '建立時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `member`
--

INSERT INTO `member` (`id`, `social_type`, `username`, `password`, `email`, `name`, `status`, `updated_at`, `created_at`) VALUES
    (1, 'email', 'example@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'rockie.lin.tw@gmail.com', '林落雞', 2, 1458858955, 1450000000);

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
    ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `status` (`status`);

--
-- 表的索引 `announce`
--
ALTER TABLE `announce`
    ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `end_time` (`end_time`);

--
-- 表的索引 `auth_assignment`
--
ALTER TABLE `auth_assignment`
    ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- 表的索引 `auth_item`
--
ALTER TABLE `auth_item`
    ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- 表的索引 `auth_item_child`
--
ALTER TABLE `auth_item_child`
    ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- 表的索引 `auth_rule`
--
ALTER TABLE `auth_rule`
    ADD PRIMARY KEY (`name`);

--
-- 表的索引 `email_check_codes`
--
ALTER TABLE `email_check_codes`
    ADD PRIMARY KEY (`id`),
  ADD KEY `checkCode` (`check_code`),
  ADD KEY `type` (`type`),
  ADD KEY `member_id` (`member_id`);

--
-- 表的索引 `member`
--
ALTER TABLE `member`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `email` (`email`),
  ADD KEY `name` (`name`),
  ADD KEY `status` (`status`),
  ADD KEY `social_type` (`social_type`),
  ADD KEY `created_at` (`created_at`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
    MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '編號', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `announce`
--
ALTER TABLE `announce`
    MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `email_check_codes`
--
ALTER TABLE `email_check_codes`
    MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `member`
--
ALTER TABLE `member`
    MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 限制导出的表
--

--
-- 限制表 `auth_assignment`
--
ALTER TABLE `auth_assignment`
    ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `auth_item`
--
ALTER TABLE `auth_item`
    ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 限制表 `auth_item_child`
--
ALTER TABLE `auth_item_child`
    ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表結構 `config`
--

CREATE TABLE `config` (
                          `id` int(10) UNSIGNED NOT NULL,
                          `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'key',
                          `category` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'site' COMMENT 'site, system',
                          `sorting` int(5) NOT NULL DEFAULT '10' COMMENT '排序',
                          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '項目',
                          `content` text COLLATE utf8mb4_unicode_ci COMMENT '內容',
                          `description` mediumtext COLLATE utf8mb4_unicode_ci COMMENT '描述',
                          `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'text, image',
                          `updated_at` int(10) UNSIGNED DEFAULT NULL,
                          `created_at` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `config`
--
ALTER TABLE `config`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`),
  ADD KEY `category` (`category`);


COMMIT;
