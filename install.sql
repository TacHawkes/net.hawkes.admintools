--
-- Table structure for table 'wcf1_admin_tools_function'
--

DROP TABLE IF EXISTS wcf1_admin_tools_function;
CREATE TABLE IF NOT EXISTS wcf1_admin_tools_function (
  functionID int(10) unsigned NOT NULL auto_increment,
  packageID int(10) unsigned NOT NULL,
  functionName varchar(255) NOT NULL,
  classPath varchar(255) NOT NULL,
  executeAsCronjob tinyint(1) unsigned NOT NULL default '0',
  saveSettings tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (functionID),
  UNIQUE KEY functionName (packageID,functionName)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'wcf1_admin_tools_function_to_cronjob'
--

DROP TABLE IF EXISTS wcf1_admin_tools_function_to_cronjob;
CREATE TABLE IF NOT EXISTS wcf1_admin_tools_function_to_cronjob (
  functionID int(10) unsigned NOT NULL,
  cronjobID int(10) unsigned NOT NULL,
  PRIMARY KEY  (functionID,cronjobID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'wcf1_admin_tools_iframe'
--

DROP TABLE IF EXISTS wcf1_admin_tools_iframe;
CREATE TABLE IF NOT EXISTS wcf1_admin_tools_iframe (
  iframeID int(10) unsigned NOT NULL auto_increment,
  menuItemID int(10) unsigned NOT NULL,
  url varchar(255) NOT NULL,
  width varchar(255) NOT NULL,
  height varchar(255) NOT NULL,
  borderWidth varchar(255) NOT NULL,
  borderColor varchar(255) NOT NULL,
  borderStyle enum('solid','dotted','dashed','double','groove','ridge','inset','outset') NOT NULL default 'solid',
  PRIMARY KEY  (iframeID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'wcf1_admin_tools_option'
--

DROP TABLE IF EXISTS wcf1_admin_tools_option;
CREATE TABLE IF NOT EXISTS wcf1_admin_tools_option (
  optionID int(10) unsigned NOT NULL auto_increment,
  packageID int(10) unsigned NOT NULL default '0',
  optionName varchar(255) NOT NULL default '',
  categoryName varchar(255) NOT NULL default '',
  optionType varchar(255) NOT NULL default '',
  optionValue mediumtext,
  validationPattern text,
  selectOptions mediumtext,
  enableOptions mediumtext,
  showOrder int(10) unsigned NOT NULL default '0',
  hidden tinyint(1) unsigned NOT NULL default '0',
  permissions text,
  options text,
  PRIMARY KEY  (optionID),
  UNIQUE KEY optionName (optionName,packageID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table 'wcf1_admin_tools_option_category'
--

DROP TABLE IF EXISTS wcf1_admin_tools_option_category;
CREATE TABLE IF NOT EXISTS wcf1_admin_tools_option_category (
  categoryID int(10) unsigned NOT NULL auto_increment,
  packageID int(10) unsigned NOT NULL default '0',
  functionID int(10) unsigned NOT NULL,
  categoryName varchar(255) NOT NULL default '',
  parentCategoryName varchar(255) NOT NULL default '',
  showOrder int(10) unsigned NOT NULL default '0',
  permissions text,
  options text,
  PRIMARY KEY  (categoryID),
  UNIQUE KEY categoryName (categoryName,packageID)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table 'wcf1_admin_tools_spider'
--

DROP TABLE IF EXISTS wcf1_admin_tools_spider;
CREATE TABLE IF NOT EXISTS wcf1_admin_tools_spider (
  spiderID int(10) unsigned NOT NULL auto_increment,
  spiderIdentifier varchar(255) default '',
  spiderName varchar(255) default '',
  spiderURL varchar(255) default '',
  PRIMARY KEY  (spiderID),
  UNIQUE KEY spiderIdentifier (spiderIdentifier)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
