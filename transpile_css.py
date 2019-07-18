
"""

Transpiles CSS and replaces with shopify theme setting values

"""

print("transpile.py running")


CSS_URL = "./shop/assets/application.css.liquid"

transpile_values = {
  "{{settings.color_2}}":"#ffffff",
  "{{settings.color_3}}": "#000000",
  "{{settings.color_4}}": "#F0F0F0",
  "{{settings.color_5}}":"#FCFCFC",
  "{{settings.color_6}}":"#999999",
  "{{settings.color_7}}":"#5B5B5B",
  "{{settings.header_color}}": "#shopify_header_color"
}


with open(CSS_URL, "r+") as f:
    css = f.read()
    found = 0
    for theme_setting, css_value in zip(transpile_values.keys(), transpile_values.values()):
        try:
            css.index(css_value)
            css = css.replace(css_value, theme_setting)
            found += 1
        except ValueError as e:
            continue
    # if changes were found than rewrite the file
    if found > 0:
        print(found)
        f.seek(0)
        f.truncate()
        f.write(css)







