
"""

Transpiles CSS and replaces with shopify theme setting values

"""


CSS_URL = "./shop/assets/application.css.liquid"

transpile_values = {
  "{{settings.color_2}}":"#ffffff",
  "{{settings.color_3}}": "#000000",
  "{{settings.color_4}}": "#F0F0F0",
  "{{settings.color_5}}":"#FCFCFC",
  "{{settings.color_6}}":"#999999",
  "{{settings.color_7}}":"#5B5B5B",
  "{{settings.color_1}}":"#0074E4"
}


with open(CSS_URL, "r+") as f:
    css = f.read()
    for theme_setting, css_value in zip(transpile_values.keys(), transpile_values.values()):
        css = css.replace(css_value, theme_setting)
    f.seek(0)
    f.truncate()
    f.write(css)







