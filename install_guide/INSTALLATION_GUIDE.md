# Installing the Pilgrimz Map Component WordPress Plugin

## Installation Steps

1. **Download the Plugin**
   - Go to the [latest release](https://github.com/pilgrimz-dev/pilgrimz-map-component-wp/releases/latest) on GitHub
   - Under **Assets**, download the file named `pilgrimz-tour-map-X.X.X.zip` (where X.X.X is the version number)
   - Save this file somewhere easy to find on your computer (like your Downloads folder)

2. **Log in to WordPress**
   - Go to your WordPress website's admin area

3. **Navigate to Plugins**
   - In the left sidebar, click on **Plugins**
   ![Plugins page](images/CleanShot%202025-10-08%20at%2017.57.55%402x.png)
   - Then click **Add New Plugin** (or **Add New**)
   ![Add new plugin](images/CleanShot%202025-10-08%20at%2017.59.25%402x.png)

4. **Upload the Plugin**
   - Click the **Upload Plugin** button at the top of the page
   ![upload plugin button](images/CleanShot%202025-10-08%20at%2017.59.56%402x.png)
   - Click **Choose File** (or **Browse**)
   - Find and select the `pilgrimz-tour-map-X.X.X.zip` file you downloaded
   - Click **Install Now**
   ![select file and upload](images/CleanShot%202025-10-08%20at%2018.00.46%402x.png)

5. **Activate the Plugin**
   - Once the upload completes, click **Activate Plugin**
   ![Activate plugin](images/CleanShot%202025-10-08%20at%2018.01.37%402x.png)
   - You should see a success message

## Updating the Plugin

1. Download the new `pilgrimz-tour-map-X.X.X.zip` from the [latest release](https://github.com/pilgrimz-dev/pilgrimz-map-component-wp/releases/latest)
2. In WordPress, go to **Plugins > Add New Plugin > Upload Plugin** and select the new zip
3. Click **Install Now**, then **Replace current with uploaded**
4. Your settings and existing shortcodes are kept

## Using the Plugin

Once activated, you can add Pilgrimz tour maps to any page or post using a shortcode:

```
[pilgrimz-tour-map tour-id="123"]
```

Replace `123` with your actual tour ID.

![Add block](images/CleanShot%202025-10-08%20at%2018.05.24%402x.png)

![select more](images/CleanShot%202025-10-08%20at%2018.05.47%402x.png)

![add shortcode](images/CleanShot%202025-10-08%20at%2018.07.10%402x.png)

### Optional Settings

You can customize the map with these options:

```
[pilgrimz-tour-map tour-id="123" language="es" hub="my-hub" width="100%" height="600px" map-type="outdoors" preview="true"]
```

- **tour-id** - Your tour ID (required)
- **language** - Map language code, such as `en`, `es` or `fr`. By default, the language of your WordPress site is used
- **hub** - Hub code provided by the Pilgrimz team for a personalized look and feel. Leave it out to use the site default (see Settings below)
- **width** - Map width, such as `100%` or `800px`. The map adapts to its container by default
- **height** - Map height, such as `600px` or `80vh`. The map is at least 300px high when no height is given
- **map-type** - Base map style: `standard`, `streets`, `outdoors`, `light` or `satellite`
- **preview** - Set to `true` to show a lightweight preview that loads the map on click, or `false` to always show the full map

## Settings

Go to **Settings > Pilgrimz Tour Map** in WordPress. The **Default Settings** section has two options:

- **Hub Code** - Applied to every map that has no `hub` in its shortcode
- **Load the map on click (lightweight preview)** - When ticked, every map on your site shows a preview first and loads the interactive map only when a visitor clicks it. To keep the full map for a single shortcode, add `preview="false"` to it

Click **Save Settings** to apply your changes.

### Eco-design note

The preview shows a static image. The interactive map, which is much heavier, loads only when a visitor clicks it. Pages load faster, use less data and consume less energy, especially for visitors who never open the map.

## Admin Area
The plugin has an admin area with instructions on how to use it and where to configure your hub code and the lightweight preview.
![admin area](images/CleanShot%202025-10-08%20at%2018.03.41%402x.png)

## Need More Help?

If you encounter any issues during installation, contact your website administrator or the Pilgrimz support team.
