<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post Approval Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical; /* Allow vertical resizing of the textarea */
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form action="<?php echo base_url('jobs/job_post_approved');?>" method="post" name="comment_form" id="comment_form">
        <input type="hidden" name="status" value="<?php echo $status; ?>">
        <input type="hidden" name="record_id" value="<?php echo $record_id; ?>">

        <label for="description">Comment:</label>
        <textarea id="comment" name="comment" rows="4" <?php if($status == '2'){echo "required";} ?> ></textarea>
        <input type="submit" style=" padding: 10px; background-color: #4CAF50;color: #fff;border: none;cursor: pointer;width: 150px; height: 40px;" name="submit" id='submit' value="Submit">
    </form>
</body>
</html>
