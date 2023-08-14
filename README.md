# Company Details Plugin

The **Company Details** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav). Store and manage company details in the settings for site wide usage.

It will add a Details tab to the settings in the admin and will save this data as details.yaml in your config folder. This information will be served as `config.details` object in the frontend.

At the moment there a these options included.

| Name | Type |
|---|---|
| Company Name | Text |
| Email | Text |
| Phone Number | Text |
| Fax Number | Text |
| Address | Textarea |
| Google Maps URL | Text |
| Opening Hours | List (Array) |
| Opening Hours/Day | Text |
| Opening Hours/Time Span | Text |

If you need more, modify it to your linking. This plugin is a simple base for customization and will not be published via GPM.

## Installation

This Plugin will not be published via GPM because it's meant to be a building block of your custom theme.

### Manual Installation dfrom zip file

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `company-details`. You can find these files on [GitHub](https://github.com/bitstarr/grav-plugin-company-details).

You should now have all the plugin files under

    /your/site/grav/user/plugins/company-details

### Manual Installation via git

You can use these commands to install the plugin:

```
cd /your/site/grav/user/plugins/
git clone https://github.com/bitstarr/grav-plugin-company-details company-details
rm -rf company-details/.git
```

The last command removes the git information from the plugin. You should include the plugin in you project repository directly so there is no need to have a connection to the original source and also versioning should be done project wide, especially if you modify the plugin.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/company-details/company-details.yaml` to `user/config/plugins/company-details.yaml` and only edit that copy.

There is not much more to configure that enable or disable the plugin.

```yaml
enabled: true
```

Note that if you use the Admin Plugin, a file with your configuration named company-details.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

In `/your/site/grav/user/plugins/company-details` you find the configuration of the admin settings form. To modify or add fields, follow the [documentation about grav forms](https://learn.getgrav.org/17/forms).

## Usage

Here is an example (with [svg-extension](https://github.com/bitstarr/grav-plugin-svg-extension) for labeling) of what you can do with the stored information in your templates:

```twig
<div class="aboutus">
    {% if config.details.address -%}
    <p>
        {{ config.details.name }}<br>
        {{ config.details.address|nl2br }}
    </p>
    {% endif -%}

    <p>
        {% if config.details.phone -%}
        {{ svg('phone', 'icon', { 'title': 'Telefon' })|raw }}
        <a href="tel:{{ config.details.phone|replace({' ': ''}) }}" class="u-tel">
            {{ config.details.phone }}
        </a>
        {% endif -%}
        {% if config.details.email -%}
        <br>
        {{ svg('mail', 'icon', { 'title': 'E-Mail' })|raw }}
        <a href="mailto:{{ config.details.email|safe_email }}">
            {{ config.details.email|safe_email }}
        </a>
        {% endif -%}
    </p>
</div>
```