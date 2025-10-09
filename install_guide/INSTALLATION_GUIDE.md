# Installing the Pilgrimz Map Component WordPress Plugin

## Installation Steps

1. **Download the Plugin**
   - You should have received a file named `pilgrimz-tour-map-X.X.X.zip` (where X.X.X is the version number)
   - Save this file somewhere easy to find on your computer (like your Downloads folder)

2. **Log in to WordPress**
   - Go to your WordPress website's admin area

3. **Navigate to Plugins**
   - In the left sidebar, click on **Plugins**
   ![Plugins page](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2017.57.55%402x.png?version%3D1759999140020)
   - Then click **Add New Plugin** (or **Add New**)
   ![Add new plugin](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2017.59.25%402x.png?version%3D1759999217472)

4. **Upload the Plugin**
   - Click the **Upload Plugin** button at the top of the page
   ![upload plugin button](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2017.59.56%402x.png?version%3D1759999237319)
   - Click **Choose File** (or **Browse**)
   - Find and select the `pilgrimz-tour-map-X.X.X.zip` file you downloaded
   - Click **Install Now**
   ![select file and upload](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2018.00.46%402x.png?version%3D1759999248682)

5. **Activate the Plugin**
   - Once the upload completes, click **Activate Plugin**
   ![Activate plugin](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2018.01.37%402x.png?version%3D1759999261046)
   - You should see a success message

## Using the Plugin

Once activated, you can add Pilgrimz tour maps to any page or post using a shortcode:

```
[pilgrimz_tour_map tour_id="123"]
```

Replace `123` with your actual tour ID.

![Add block](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2018.05.24%402x.png?version%3D1759999363046)

![select more](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2018.05.47%402x.png?version%3D1759999324961)

![add shortcode](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2018.07.10%402x.png?version%3D1759999355162)



### Optional Settings

You can customize the map with these options:

```
[pilgrimz_tour_map tour_id="123" language="es" hub="green" height="600px"]
```

- **tour_id** - Your tour ID (required)
- **language** - Map language: `en` (English), `es` (Spanish), `fr` (French), `it` (Italian), `de` (German), `nl` (Dutch), `pt` (Portuguese)
- **hub** - Color theme: `default` or `green`
- **height** - Map height (default: 500px)

## Admin Area
The plugin have an admin area with instructions in how to use the plugin and configure your hub code.
![admin area](https://file%2B.vscode-resource.vscode-cdn.net/Users/47221295h/dev/pilgrimz/pilgrimz-map-component-wp/install_guide/images/CleanShot%202025-10-08%20at%2018.03.41%402x.png?version%3D1759999270373)

## Need More Help?

If you encounter any issues during installation, contact your website administrator or the Pilgrimz support team.
