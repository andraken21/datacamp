<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // =========================================================
        // TRIGGER: after_lesson_completed
        // Otomatis tambah XP dan update streak setiap kali
        // user menyelesaikan lesson (is_completed berubah jadi 1)
        // =========================================================
        DB::unprepared('DROP TRIGGER IF EXISTS after_lesson_completed');

        DB::unprepared('
            CREATE TRIGGER after_lesson_completed
            AFTER UPDATE ON lesson_progress
            FOR EACH ROW
            BEGIN
                -- Hanya jalan kalau is_completed berubah dari 0 ke 1
                IF OLD.is_completed = 0 AND NEW.is_completed = 1 THEN

                    -- Tambah XP +10 per lesson selesai
                    UPDATE users
                    SET xp = xp + 10
                    WHERE id = NEW.user_id;

                    -- Update streak:
                    -- Kalau last_activity = kemarin → streak + 1
                    -- Kalau last_activity = hari ini → streak tetap
                    -- Kalau lebih dari kemarin → reset streak ke 1
                    UPDATE users
                    SET
                        streak = CASE
                            WHEN last_activity = CURDATE() - INTERVAL 1 DAY THEN streak + 1
                            WHEN last_activity = CURDATE() THEN streak
                            ELSE 1
                        END,
                        last_activity = CURDATE()
                    WHERE id = NEW.user_id;

                END IF;
            END
        ');

        // =========================================================
        // STORED PROCEDURE: GetLeaderboard
        // Ambil top N user berdasarkan XP tertinggi
        // Cara pakai: CALL GetLeaderboard(5);
        // =========================================================
        DB::unprepared('DROP PROCEDURE IF EXISTS GetLeaderboard');

        DB::unprepared('
            CREATE PROCEDURE GetLeaderboard(IN top_n INT)
            BEGIN
                SELECT
                    id,
                    name,
                    xp,
                    streak,
                    RANK() OVER (ORDER BY xp DESC) AS `rank`
                FROM users
                ORDER BY xp DESC
                LIMIT top_n;
            END
        ');

        // =========================================================
        // STORED PROCEDURE: GetUserXPSummary
        // Ambil ringkasan XP seorang user:
        // total XP, streak, rank di leaderboard, progress ke 250 XP
        // Cara pakai: CALL GetUserXPSummary(1);
        // =========================================================
        DB::unprepared('DROP PROCEDURE IF EXISTS GetUserXPSummary');

        DB::unprepared('
            CREATE PROCEDURE GetUserXPSummary(IN p_user_id BIGINT)
            BEGIN
                SELECT
                    u.id,
                    u.name,
                    u.xp,
                    u.streak,
                    u.last_activity,
                    LEAST(ROUND(u.xp / 250 * 100), 100) AS xp_percent,
                    (
                        SELECT COUNT(*) + 1
                        FROM users u2
                        WHERE u2.xp > u.xp
                    ) AS leaderboard_rank
                FROM users u
                WHERE u.id = p_user_id;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_lesson_completed');
        DB::unprepared('DROP PROCEDURE IF EXISTS GetLeaderboard');
        DB::unprepared('DROP PROCEDURE IF EXISTS GetUserXPSummary');
    }
};