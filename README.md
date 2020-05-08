<p align="center">
    <img src="https://cdn.jsdelivr.net/gh/sy-records/staticfile/images/202005/random-look.png" alt="Random Look" />
</p>

# Random Look

给WordPress添加随便看看，顾名思义就是随机给出文章来看看，很小但很实用。

博客原文：[WordPress添加随便看看](https://qq52o.me/165.html)

## 安装

WordPress后台安装插件，搜索：`Random Look`。 [WordPress Plugin](https://wordpress.org/plugins/random-look)

## 修改配置
    
* 方法一：在 WordPress 插件管理页面有设置按钮，进行设置
* 方法二：在 WordPress 后台管理左侧导航栏`设置`下`随便看看设置`，点击进入设置页面

![](https://cdn.jsdelivr.net/gh/sy-records/random-look/screenshot-1.png)

## 选择随机方式

有两种随机方式，一种直接随机，另外一种可以指定分类和文章类型进行随机

## 使用方式

* 访问你网站的域名 `/?random` 即可看到效果；

> 如：[qq52o.me/?random](https://qq52o.me/?random)

* 如果要在导航菜单显示的话，在后台新建一个自定义 URL 菜单即可；

当你选择第二种指定分类和文章类型时，需要增加其他两个参数

`random_cat_id`和`random_post_type`，分别表示分类ID和文章类型