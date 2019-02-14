class Points {
    constructor() {
    }
    async  get_id(customer_id) {
        const point = await db1.points.where({ customer_id: String(customer_id) }).first();

        if (point != undefined) {
            return point.id_point;
        }

        return false;
    }
    async point_customer(customer_id) {
        const point = await db1.points.where({ customer_id: String(customer_id) }).first();

        if (point != undefined) {
            return point.points;
        }

        return false;
    }

    async  save_point(points, customer_id, detail) {

        if (await objAppconfig.item('system_point') == 1) {
            try {
                let query=0;
                let data_point = {
                    'customer_id': customer_id,
                    'points': Number(await this.point_customer(customer_id)) + Number(points)
                };
                let id_point = await this.get_id(customer_id);

                if (id_point > 0) {
                     query = await db1.points.where({ id_point: id_point }).modify(data_point);
                }
                else {
                    await db1.points.add(data_point);
                }
            } catch (e) {
                return -1
            }
        }
        return 1;
    }
}