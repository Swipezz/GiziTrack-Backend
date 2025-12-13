<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API AIO</title>
</head>
<body>
    <form action="/api/login" method="post">
        <input type="text" name="username" value="USERNAME123">
        <input type="text" name="password" value="PASSWORD123">
        <button type="submit">/api/login POST</button>
    </form>
    <hr>
    <form action="/api/register" method="post">
        <input type="text" name="username" value="USERNAME123">
        <input type="text" name="password" value="PASSWORD123">
        <input type="text" name="office" value="OFFICE123">
        <input type="number" name="employee" value="1">
        <button type="submit">/api/register POST</button>
    </form>
    <hr>
    <form action="/api/logout" method="post">
        <button type="submit">/api/logout POST</button>
    </form>
    <hr>
    <form action="/api/profile" method="get">
        <button type="submit">/api/profile GET</button>
    </form>
    <hr>
    <form action="/api/school" method="get">
        <button type="submit">/api/school GET</button>
    </form>
    <hr>
    <form action="/api/school" method="post" enctype="multipart/form-data">
        <input type="text" name="name" value="NAME123">
        <input type="text" name="location" value="LOCATION123">
        <input type="number" name="total_student" value="1">
        <input type="number" name="total_meal" value="2">
        <input type="text" name="type_allergy" value="TYPEALLERY123">
        <input type="file" name="logo" accept="image/*">
        <button type="submit">/api/school POST</button>
    </form>
    <hr>
    <form action="" method="get" onsubmit="this.action='/api/school/' + this.id.value">
        <input type="number" name="id" value="1">
        <button type="submit">/api/school/{id} GET</button>
    </form>
    <hr>
    <form action="" method="post" onsubmit="this.action='/api/school/' + this.id.value" enctype="multipart/form-data">
        @method('PUT')
        <input type="number" name="id" value="1">
        <input type="text" name="name" value="NAME123">
        <input type="text" name="location" value="LOCATION123">
        <input type="number" name="total_student" value="2">
        <input type="number" name="total_meal" value="3">
        <input type="text" name="type_allergy" value="TYPEALLERY123">
        <input type="file" name="logo" accept="image/*">
        <button type="submit">/api/school/{id} PUT</button>
    </form>
    <hr>
    <form action="" method="post" onsubmit="this.action='/api/school/' + this.id.value">
        @method('DELETE')
        <input type="number" name="id" value="1">
        <button type="submit">/api/school/{id} DELETE</button>
    </form>
    <hr>
    <form action="/api/survey/food" method="post">
        <select class="school-select" name="school" required>
            <option value="">-- Pilih Sekolah --</option>
        </select>
        <input type="text" name="food[]" value="FOOD1">
        <input type="text" name="food[]" value="FOOD2">
        <input type="text" name="food[]" value="FOOD3">
        <input type="number" name="total[]" value="1">
        <input type="number" name="total[]" value="2">
        <input type="number" name="total[]" value="3">
        <button type="submit">/api/survey/food POST</button>
    </form>
    <hr>
    <form action="/api/survey/allergy" method="post">
        <select class="school-select" name="school" required>
            <option value="">-- Pilih Sekolah --</option>
        </select>
        <input type="text" name="allergy" value="ALLERGY123">
        <button type="submit">/api/survey/allergy POST</button>
    </form>
    <hr>
    <script>
        fetch('/api/school')
            .then(res => res.json())
            .then(res => {
                let selects = document.querySelectorAll('.school-select');

                selects.forEach(select => {
                    res.data.forEach(school => {
                        let opt = document.createElement('option');
                        opt.value = school.name;
                        opt.textContent = school.name;
                        select.appendChild(opt);
                    });
                });
            });
    </script>
</body>
</html>