
"""

Transpiles CSS and replaces with shopify theme setting values

"""

print("transpile.py running")


CSS_URL = "./shop/assets/application.css.liquid"

transpile_values = {
  "{{settings.color_1}}": "#0074E4",
  "{{settings.color_2}}":"#ffffff",
  "{{settings.color_3}}": "#000000",
  "{{settings.color_4}}": "#F0F0F0",
  "{{settings.color_5}}":"#FCFCFC",
  "{{settings.color_6}}":"#999999",
  "{{settings.color_7}}":"#5B5B5B",
  "{{settings.header_color}}": "#shopify_header_color",
   "{{header_font}}": "BentonSans Condensed",
  "{{body_font}}": "BentonSans",

}

font_logic = '''

{% assign header_custom_font = settings.header_custom_font %}
{% assign body_custom_font = settings.body_custom_font %}
{% assign body_font_select = settings.body_font_select %}
{% assign header_font_select = settings.heading_font_select %}

{% assign header_font = header_font_select %}
{% assign body_font = body_font_select %}


{%if header_custom_font != "none" %}
    {% assign header_font = header_custom_font %}
{% else %}
    {{header_font | font_face}}
    {% assign header_font = header_font.family | replace: '"', ""  %}
    
{% endif %}

{%if body_custom_font != "none" %}
    {% assign body_font = body_custom_font  %}
{% else %}
    {{body_font  | font_face }}
    {% assign body_font = body_font.family | replace: """, "" %}
    
{% endif %}

@font-face {
  font-family: 'BentonSans';
  src: url('{{ "BentonSans-Thin.eot" | asset_url }}');
  src: url('{{ "BentonSans-Thin.eot?#iefix" | asset_url }}') format('embedded-opentype'),
       url('{{ "BentonSans-Thin.woff2" | asset_url }}') format('woff2'),
       url('{{ "BentonSans-Thin.woff" | asset_url }}') format('woff'),
       url('{{ "BentonSans-Thin.ttf" | asset_url }}') format('truetype'),
       url('{{ "BentonSans-Thin.svg#BentonSans-Thin" | asset_url }}') format('svg');
  font-weight: 100;
  font-style: normal;
}

@font-face {
  font-family: 'BentonSans';
  src: url('{{ "BentonSans-Book.eot" | asset_url }}');
  src: url('{{ "BentonSans-Book.eot?#iefix" | asset_url }}') format('embedded-opentype'),
       url('{{ "BentonSans-Book.woff2" | asset_url }}') format('woff2'),
       url('{{ "BentonSans-Book.woff" | asset_url }}') format('woff'),
       url('{{ "BentonSans-Book.ttf" | asset_url }}') format('truetype'),
       url('{{ "BentonSans-Book.svg#BentonSans-Book" | asset_url }}') format('svg');
  font-weight: 300;
  font-style: normal;
}

@font-face {
  font-family: 'BentonSans';
  src: url('{{ "BentonSans.eot" | asset_url }}');
  src: url('{{ "BentonSans.eot?#iefix" | asset_url }}') format('embedded-opentype'),
       url('{{ "BentonSans.woff2" | asset_url }}') format('woff2'),
       url('{{ "BentonSans.woff" | asset_url }}') format('woff'),
       url('{{ "BentonSans.ttf" | asset_url }}') format('truetype'),
       url('{{ "BentonSans.svg#BentonSans" | asset_url }}') format('svg');
  font-weight: normal;
  font-style: normal;
}

@font-face {
  font-family: 'BentonSans';
  src: url('{{ "BentonSans-Medium.eot" | asset_url }}');
  src: url('{{ "BentonSans-Medium.eot?#iefix" | asset_url }}') format('embedded-opentype'),
       url('{{ "BentonSans-Medium.woff2" | asset_url }}') format('woff2'),
       url('{{ "BentonSans-Medium.woff" | asset_url }}') format('woff'),
       url('{{ "BentonSans-Medium.ttf" | asset_url }}') format('truetype'),
       url('{{ "BentonSans-Medium.svg#BentonSans-Medium" | asset_url }}') format('svg');
  font-weight: 500;
  font-style: normal;
}

@font-face {
    font-family: 'BentonSans';
    src: url('{{ "BentonSans-Bold.eot" | asset_url }}');
    src: url('{{ "BentonSans-Bold.eot?#iefix" | asset_url }}') format('embedded-opentype'),
         url('{{ "BentonSans-Bold.woff2" | asset_url }}') format('woff2'),
         url('{{ "BentonSans-Bold.woff" | asset_url }}') format('woff'),
         url('{{ "BentonSans-Bold.ttf" | asset_url }}') format('truetype'),
         url('{{ "BentonSans-Bold.svg#BentonSans-Bold" | asset_url }}') format('svg');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'BentonSans Condensed';
    src: url('{{ "BentonSans-CondensedBook.eot" | asset_url }}');
    src: url('{{ "BentonSans-CondensedBook.eot?#iefix" | asset_url }}') format('embedded-opentype'),
         url('{{ "BentonSans-CondensedBook.woff2" | asset_url }}') format('woff2'),
         url('{{ "BentonSans-CondensedBook.woff" | asset_url }}') format('woff'),
         url('{{ "BentonSans-CondensedBook.ttf" | asset_url }}') format('truetype'),
         url('{{ "BentonSans-CondensedBook.svg#BentonSans-CondensedBook" | asset_url }}') format('svg');
    font-weight: normal;
    font-style: normal;
}

@font-face {
    font-family: 'BentonSans Condensed';
    src: url('{{ "BentonSans-CondensedBold.eot" | asset_url }}');
    src: url('{{ "BentonSans-CondensedBold.eot?#iefix" | asset_url }}') format('embedded-opentype'),
         url('{{ "BentonSans-CondensedBold.woff2" | asset_url }}') format('woff2'),
         url('{{ "BentonSans-CondensedBold.woff" | asset_url }}') format('woff'),
         url('{{ "BentonSans-CondensedBold.ttf" | asset_url }}') format('truetype'),
         url('{{ "BentonSans-CondensedBold.svg#BentonSans-CondensedBold" | asset_url }}') format('svg');
    font-weight: bold;
    font-style: normal;
}

@font-face {
    font-family: 'BentonSans Condensed';
    src: url('{{ "BentonSans-CondensedBlack.eot" | asset_url }}');
    src: url('{{ "BentonSans-CondensedBlack.eot?#iefix" | asset_url }}') format('embedded-opentype'),
         url('{{ "BentonSans-CondensedBlack.woff2" | asset_url }}') format('woff2'),
         url('{{ "BentonSans-CondensedBlack.woff" | asset_url }}') format('woff'),
         url('{{ "BentonSans-CondensedBlack.ttf" | asset_url }}') format('truetype'),
         url('{{ "BentonSans-CondensedBlack.svg#BentonSans-CondensedBlack" | asset_url }}') format('svg');
    font-weight: 900;
    font-style: normal;
}


'''


with open(CSS_URL, "r+") as f:
    css = f.read()
    found = 0
    for theme_setting, css_value in zip(transpile_values.keys(), transpile_values.values()):
        try:
            css.index(css_value)
            print(css.index(css_value), css_value)

            css = css.replace(css_value, theme_setting)
            found += 1
        except ValueError as e:
            continue
    # if changes were found than rewrite the file
    if found > 2:
        print(found)
        f.seek(0)
        f.truncate()
        f.write(font_logic + css)








