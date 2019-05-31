<?php

use yii\db\Migration;

class m180618_135050_install extends Migration
{
    public function safeUp()
    {
        $this->execute("

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cms_content_blocks
-- ----------------------------
DROP TABLE IF EXISTS `cms_content_blocks`;
CREATE TABLE `cms_content_blocks` (
  `contentID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` mediumint(8) unsigned DEFAULT NULL,
  `contentBlockName` varchar(100) DEFAULT NULL COMMENT 'Block identifier for template',
  `content` longtext,
  `Pages_pageID` int(10) unsigned NOT NULL,
  `contentType` enum('XHTML','RSS','VIDEO','FLASH','JAVASCRIPT','MODULE','CSS','CSSLINK','JAVASCRIPTFILE') NOT NULL DEFAULT 'XHTML',
  PRIMARY KEY (`contentID`,`Pages_pageID`),
  KEY `fk_Content_Pages1` (`Pages_pageID`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_content_blocks_lang
-- ----------------------------
DROP TABLE IF EXISTS `cms_content_blocks_lang`;
CREATE TABLE `cms_content_blocks_lang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contenct_block_id` int(11) unsigned NOT NULL,
  `language` varchar(10) DEFAULT NULL,
  `content` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_document_version
-- ----------------------------
DROP TABLE IF EXISTS `cms_document_version`;
CREATE TABLE `cms_document_version` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` int(10) unsigned NOT NULL,
  `version` mediumint(9) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `published_yn` tinyint(1) DEFAULT NULL,
  `published_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `owner_user_id` int(10) unsigned DEFAULT NULL,
  `nodeType` enum('document','controller','forward') DEFAULT NULL,
  `nodeProperties` text,
  `viewLayout` varchar(255) DEFAULT NULL,
  `viewTemplate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_document_version_content
-- ----------------------------
DROP TABLE IF EXISTS `cms_document_version_content`;
CREATE TABLE `cms_document_version_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version_id` int(11) unsigned NOT NULL,
  `contentType` enum('XHTML','RSS','VIDEO','FLASH','JAVASCRIPT','MODULE','CSS','CSSLINK','JAVASCRIPTFILE') NOT NULL DEFAULT 'XHTML',
  `content` longtext,
  `sort_order` mediumint(8) unsigned DEFAULT NULL,
  `contentBlockName` varchar(100) DEFAULT NULL COMMENT 'Block identifier for template',
  PRIMARY KEY (`id`,`version_id`),
  KEY `fk_Content_Pages1` (`version_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_document_version_content_lang
-- ----------------------------
DROP TABLE IF EXISTS `cms_document_version_content_lang`;
CREATE TABLE `cms_document_version_content_lang` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version_content_id` int(11) unsigned NOT NULL,
  `language` varchar(10) DEFAULT NULL,
  `content` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for cms_node_viewlog
-- ----------------------------
DROP TABLE IF EXISTS `cms_node_viewlog`;
CREATE TABLE `cms_node_viewlog` (
  `node_id` int(10) unsigned NOT NULL,
  `date` date DEFAULT NULL,
  `time` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ip_address` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_redirect
-- ----------------------------
DROP TABLE IF EXISTS `cms_redirect`;
CREATE TABLE `cms_redirect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `container_app_id` int(10) unsigned DEFAULT NULL,
  `redirect_from` varchar(255) NOT NULL,
  `redirect_to` varchar(255) DEFAULT NULL,
  `updated_on` datetime NOT NULL,
  `deleted_yn` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `container_app_id` (`container_app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cms_router_node
-- ----------------------------
DROP TABLE IF EXISTS `cms_router_node`;
CREATE TABLE `cms_router_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `container_app_id` int(10) unsigned DEFAULT NULL,
  `version_id` int(11) unsigned DEFAULT NULL,
  `nodeBackendName` varchar(255) NOT NULL,
  `nodeRoute` varchar(255) NOT NULL,
  `nodeParentRoute` varchar(255) DEFAULT NULL,
  `nodeType` enum('document','controller','forward') NOT NULL,
  `nodeEnabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `nodeProperties` text,
  `nodeContentPageID` int(10) unsigned DEFAULT NULL,
  `nodeHomePage` tinyint(1) DEFAULT NULL COMMENT 'Whether this page should be recognized as homepage',
  `nodeAccessLockType` enum('filter','role','bizrule','inherit') DEFAULT NULL,
  `nodeAccessLockConfig` varchar(255) DEFAULT NULL COMMENT 'Config (serialized array) for filter, role name for role-based permission check, PHP code for bizrule or NULL for free or inherited acceess',
  `nodeLastEdit` datetime NOT NULL,
  `nodeOrder` mediumint(4) unsigned DEFAULT NULL,
  `viewLayout` varchar(100) DEFAULT NULL,
  `viewTemplate` varchar(200) DEFAULT NULL,
  `deleted_yn` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sitemap_yn` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nodeHomePage` (`nodeHomePage`),
  KEY `Pages_pageID` (`nodeContentPageID`) USING BTREE,
  KEY `fk_CmsRouterNode_nodeContentPageID` (`nodeContentPageID`) USING BTREE,
  KEY `nodeEnabled` (`nodeEnabled`) USING BTREE,
  KEY `deleted_yn` (`deleted_yn`),
  KEY `cms_router_node_sitemap` (`sitemap_yn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
    }

    public function down()
    {
        echo "m180618_135050_install cannot be reverted.\n";

        return false;
    }
}
